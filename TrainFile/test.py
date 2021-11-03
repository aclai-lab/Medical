#!/usr/bin/python
# Code based on https://www.analyticsvidhya.com/blog/2020/07/transfer-learning-for-nlp-fine-tuning-bert-for-text-classification/.
# test.py

# import warnings

# warnings.filterwarnings('alw')

# Import libraries.
import numpy as np

# pandas = Python Data Analysis Library.
import pandas as pd

# PyTorch.
import torch
import torch.nn as nn

# Sci-Kit Learn.
import sklearn
from sklearn.model_selection import train_test_split
from sklearn.metrics import classification_report

# Huggingface Transformers.
import transformers
from transformers import AutoModel, BertTokenizerFast

# Needed for the histogram.
import matplotlib.pyplot as plt
import time

#for argoment input
import sys



nameFileCsv = sys.argv[1]

# nameFileTxt = sys.argv[2]

# f = open(nameFileTxt,"r")

# line = f.read()

# nLabel = len(line.split(';'))-1

# print (nLabel)

# print (nameFileCsv)

# f.close()


# Needed to compute the class weights.
from sklearn.utils.class_weight import compute_class_weight

# Specify the GPU.
# Actually, if this code runs on a computer with a GPU which is not CUDA-enabled, the work will be done entirely by the CPU.
device=torch.device('cpu')#'cuda' if torch.cuda.is_available() else 

# Read the dataset into a pandas dataframe.
df=pd.read_csv(nameFileCsv)#'publications_to_train_the_model.csv' nameFileCsv

# Print the first 5 rows.
# For every print, flush=True is needed, otherwise the Java program calling this script would never read these prints.
# print(df.head(), flush=True)

# # Return the dimensions of the dataset ("shape").
# print(df.shape, flush=True)

# Check the class distribution.
print("distribuzione classe",df['label'].value_counts(normalize=True), flush=True)
nLabel = df['label'].nunique()

# Split train dataset into train, validation and test sets.
# The example dataset consists of 2 columns, "label" and "text", where "text" is the abstract and "label" is a binary flag such that 1 means "relevant" and 0 means "not relevant".
# We will fine-tune the model using the train set and the validation set, and make predictions for the test set.
train_text, temp_text, train_labels, temp_labels=train_test_split(df['text'], df['label'], random_state=2018, test_size=0.4, stratify=df['label'])
val_text, test_text, val_labels, test_labels=train_test_split(temp_text, temp_labels, random_state=2018, test_size=0.5, stratify=temp_labels)

# Import BioBERT model and BioBERT tokenizer.
# Since the folder is in the same path of this source code, there is no need to wait for the download.
biobert=AutoModel.from_pretrained('/home/mattia/medicalSearch3.0/biobert-base-cased-v1.1')
tokenizer=BertTokenizerFast.from_pretrained('/home/mattia/medicalSearch3.0/biobert-base-cased-v1.1')

# Tokenization.
# Get the length of all the texts in the training set.
seq_len=[len(i.split()) for i in train_text]

# Create a histogram to have a look at the distribution of the sequence lengths in the train set, in order to find the right padding length.
histogram=pd.Series(seq_len).hist(bins=30)
# plt.show()

# Set an adequate sequence length. Since the messages in the dataset are of varying length, we will use padding to make all the messages have the same length. 
# If we use the actual maximum sequence length, padding tokens will not be going to help the model learning anything useful, but will actually slow the training down.
max_seq_len=250

#Tokenize and encode sequences in the training set, test set and validation set.
# Training set.
tokens_train=tokenizer.batch_encode_plus(train_text.tolist(), max_length=max_seq_len, padding='max_length', truncation=True, return_token_type_ids=False)

# Validation set.
tokens_val=tokenizer.batch_encode_plus(val_text.tolist(), max_length=max_seq_len, padding='max_length', truncation=True, return_token_type_ids=False)

# Test set.
tokens_test=tokenizer.batch_encode_plus(test_text.tolist(), max_length=max_seq_len, padding='max_length', truncation=True, return_token_type_ids=False)

# Convert the integer sequences to tensors (i.e. the generalization of numbers, vectors, matrices...).
# Training set.
train_seq=torch.tensor(tokens_train['input_ids'])
train_mask=torch.tensor(tokens_train['attention_mask'])
train_y=torch.tensor(train_labels.tolist())

# Validation set.
val_seq=torch.tensor(tokens_val['input_ids'])
val_mask=torch.tensor(tokens_val['attention_mask'])
val_y=torch.tensor(val_labels.tolist())

# Test set.
test_seq=torch.tensor(tokens_test['input_ids'])
test_mask=torch.tensor(tokens_test['attention_mask'])
test_y=torch.tensor(test_labels.tolist())

# Create DataLoaders.
# We will create DataLoaders for both train and validation set.
# These DataLoaders will pass batches of train data and validation data as input to the model during the training phase.
from torch.utils.data import TensorDataset, DataLoader, RandomSampler, SequentialSampler

# Batch size.
batch_size=32

# Wrap tensors (training set).
train_data=TensorDataset(train_seq, train_mask, train_y)

# Sampler for sampling the data during training (train set).
train_sampler=RandomSampler(train_data)

# DataLoader for training set.
train_dataloader=DataLoader(train_data, sampler=train_sampler, batch_size=batch_size)

# Wrap tensors (validation set).
val_data=TensorDataset(val_seq, val_mask, val_y)

# Sampler for sampling the data during training (validation set).
val_sampler=SequentialSampler(val_data)

# DataLoader for validation set.
val_dataloader=DataLoader(val_data, sampler=val_sampler, batch_size=batch_size)

# Freeze BioBERT parameters.
#Freezing all the parameters means preventing any update of model weights during fine-tuning.
#So, we will train only the added layers (i.e. the old values for the original BioBERT parameters do not change).
for param in biobert.parameters():
    param.requires_grad=False

# Model architecture.
# Define the architecture. It will have the old BERT with a dense layer with ReLU (ReLU, standing for Rectifying Linear Unit, is an activation function such that  ReLU(x)=max(0,x)), 
# followed by a dropout layer (p=0.5), followed by another dense layer for binary classification (i.e. two outputs), and eventually a softmax layer.
class BERT_Arch(nn.Module):
	# Constructor.
    def __init__(self, bert):
      super(BERT_Arch, self).__init__()
      self.bert=bert 
      
      # Dropout layer.
      self.dropout=nn.Dropout(0.5)
      
      # ReLU activation function.
      self.relu=nn.ReLU()

      # Dense layer 1.
      self.fc1=nn.Linear(768,512)
      #self.fc2=nn.Linear(512,512)

      # Dense layer 2 (output layer).
	  # The second argument "2" represents the choice between 0 and 1.
      self.fc3=nn.Linear(512,nLabel)

      # Softmax activation function.
      self.softmax=nn.LogSoftmax(dim=1)

    # Define the forward pass.
    def forward(self, sent_id, mask):
      # Pass the inputs to the model.
      # The first underscore means "I am not interested in this field".
	  # [:2] prevents an error.
      _, cls_hs=self.bert(sent_id, attention_mask=mask)[:2]
      x=self.fc1(cls_hs)
      x=self.relu(x)
      x=self.dropout(x)
      #x=self.fc2(x)
      #x=self.relu(x)
      #x=self.dropout(x)

      # Output layer.
      x=self.fc3(x)
      
      # Apply softmax activation.
      x=self.softmax(x)
      return x

# Pass the pre-trained BERT to our defined architecture.
model=BERT_Arch(biobert)

# Push the model to the GPU (or the CPU in case CUDA is not available).
model=model.to(device)

# Import optimizer from huggingface, and set optimizer.
# We will use AdamW as our optimizer.
# It is an improved version of the Adam optimizer.
from transformers import AdamW
optimizer=AdamW(model.parameters(), lr=1e-3) # lr stands for Learning rate. Here it is set to 0.001.

# Needed to compute the class weights.
from sklearn.utils.class_weight import compute_class_weight

# We will first compute class weights for the labels in the train set and then pass these weights to the loss function so that it takes care of a potential class imbalance.
# Warning: this way of passing parameters is deprecated and will be removed from sklearn 0.25!
class_weights=compute_class_weight('balanced', np.unique(train_labels), train_labels)
# print("Class weights: ", class_weights)
weights=torch.tensor(class_weights, dtype=torch.float)
weights=weights.to(device)

# Loss function.
cross_entropy=nn.NLLLoss(weight=weights)

# Number of training epochs.
epochs=2

# Function to train the model.
def train():
    model.train()
    total_loss, total_accuracy=0, 0

    # Empty list to save model predictions.
    total_preds=[]

    # Iterate over batches.
    for step, batch in enumerate(train_dataloader):
        # Progress update after every 50 batches.
        if step%50==0 and not step==0:
            print(' Batch {:>5,} of {:>5,}.'.format(step, len(train_dataloader)), flush=True)

        # Push the batch to the GPU.
        batch=[r.to(device) for r in batch]
        sent_id, mask, labels=batch

        # Clear previously calculated gradients.
        model.zero_grad()

        # Get model predictions for the current batch.
        preds=model(sent_id, mask)

        # Compute the loss between actual and predicted values.
        loss=cross_entropy(preds, labels)

        # Add on to the total loss.
        total_loss=total_loss+loss.item()

        # Backward pass to calculate the gradients.
        loss.backward()

        # Clip the gradients to 1.0. It helps in preventing the exploding gradient problem.
        torch.nn.utils.clip_grad_norm_(model.parameters(), 1.0)

        # Update parameters.
        optimizer.step()

        # Model predictions are stored on GPU (or the CPU in case CUDA is not available). So, push it to the CPU.
        preds=preds.detach().cpu().numpy()

        # Append the model predictions.
        total_preds.append(preds)

    # Compute the training loss of the epoch.
    avg_loss=total_loss/len(train_dataloader)

    # Predictions are in the form of (no. of batches, size of batch, no. of classes). Reshape the predictions in the form of (no. of samples, no. of classes).
    total_preds=np.concatenate(total_preds, axis=0)

    # Return the loss and predictions.
    return avg_loss, total_preds

# We will use the following function to evaluate the model.
# It will use the validation set data.
def evaluate():
    print("\nEvaluating...", flush=True)

    # Deactivate dropout layers.
    model.eval()
    total_loss, total_accuracy=0, 0

    # Empty list to save model predictions.
    total_preds=[]

    # Iterate over batches.
    for step, batch in enumerate(val_dataloader):
        # Progress update after every 50 batches.
        if step%50==0 and not step==0:
            # Calculate elapsed time in minutes.
            elapsed=format_time(time.time()-t0)

            # Report progress.
            print(' Batch {:>5,} of {:>5,}.'.format(step, len(val_dataloader)), flush=True)

        # Push the batch to the GPU (or the CPU if CUDA is not available).
        batch=[t.to(device) for t in batch]
        sent_id, mask, labels=batch

        # Deactivate autograd.
        with torch.no_grad():
            # Model predictions.
            preds=model(sent_id, mask)

            # Compute the validation loss between actual and predicted values.
            loss=cross_entropy(preds, labels)
            total_loss=total_loss+loss.item()
            preds=preds.detach().cpu().numpy()
            total_preds.append(preds)

    # Compute the validation loss of the epoch.
    avg_loss=total_loss/len(val_dataloader)

    # Reshape the predictions in the form of (no. of samples, no. of classes).
    total_preds=np.concatenate(total_preds, axis=0)
    return avg_loss, total_preds

# Set initial loss to infinite.
best_valid_loss=float('inf')

# Empty lists to store training and validation loss of each epoch.
train_losses=[]
valid_losses=[]

# Measuring training time (start).
start_time=time.time()

# For each epoch...
for epoch in range(epochs):
    print('\n Epoch {:} / {:}'.format(epoch+1, epochs), flush=True)

    # Train model.
    train_loss, _=train()

    # Evaluate model.
    valid_loss, _=evaluate()

    # Save the best model.
    if valid_loss<best_valid_loss:
        best_valid_loss=valid_loss
        torch.save(model.state_dict(), '/home/mattia/medicalSearch3.0/saved_weights.pt')

    # # Append training and validation loss.
    # train_losses.append(train_loss)
    # valid_losses.append(valid_loss)
    # print(f'\nTraining Loss: {train_loss:.3f}', flush=True)
    # print(f'Validation Loss: {valid_loss:.3f}', flush=True)

# print('\nyeah boy\n')
# Measuring time (elapsed time).
print("--- %s seconds needed to train ---" % (time.time() - start_time), flush=True)

path='/home/mattia/medicalSearch3.0/saved_weights.pt'
model.load_state_dict(torch.load(path))

with torch.no_grad():
    preds=model(test_seq.to(device), test_mask.to(device))
    preds=preds.detach().cpu().numpy()

preds=np.argmax(preds, axis=1)

# Saving the classification report as a dictionary.
report=classification_report(test_y, preds, output_dict=True)
print(report, flush=True)

# Confusion matrix.
# print(pd.crosstab(test_y, preds), flush=True)

# Confusion matrix but with sklearn.
from sklearn.metrics import multilabel_confusion_matrix

# The confusion_matrix function accepts the same parameters as pandas.crosstab.
# The ravel method, as shown in https://scikit-learn.org/stable/modules/generated/sklearn.metrics.confusion_matrix.html for the binary case, allows to find true positives, false positives, true negatives, false negatives.
# tn, fp, fn, tp=confusion_matrix(test_y, preds).ravel()

print(multilabel_confusion_matrix(test_y, preds))

f=open('/home/mattia/medicalSearch3.0/TrainFile/confusion_matrix.txt', 'w')

f.write(str(multilabel_confusion_matrix(test_y, preds)))

f.close()
# Open a .txt file in writing. Here we will write the required values that the Java program will need.
f=open('/home/mattia/medicalSearch3.0/TrainFile/current_status.txt', 'w')
f.write('Accuracy: '+str((report['accuracy'])*100)+'%')
# f.write('True positives: '+str(tp)+'\n')
# f.write('False positives: '+str(fp)+'\n')
# f.write('True negatives: '+str(tn)+'\n')
# f.write('False negatives: '+str(fn)+'\n')
f.close()
