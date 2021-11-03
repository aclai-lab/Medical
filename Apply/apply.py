#!/usr/bin/python
# Code based on https://www.analyticsvidhya.com/blog/2020/07/transfer-learning-for-nlp-fine-tuning-bert-for-text-classification/.
# apply.py : it is a variation of test.py because it skips the part of training the model and saving the weights.

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

# Needed to compute the class weights.
from sklearn.utils.class_weight import compute_class_weight

import resource,sys

import csv

# resource.setrlimit(resource.RLIMIT_STACK, (resource.RLIM_INFINITY, resource.RLIM_INFINITY))


nameFileCsv = sys.argv[1]

size = int(sys.argv[2])

# size = 2

# Specify the GPU.
# Actually, if this code runs on a computer with a GPU which is not CUDA-enabled, the work will be done entirely by the CPU.
device=torch.device('cpu')#'cuda' if torch.cuda.is_available() else 

# Read the dataset into a pandas dataframe.
df=pd.read_csv(nameFileCsv)

# Print the first 5 rows.
print(df.head(), flush=True)

# Return the dimensions of the dataset ("shape").
print(df.shape, flush=True)

# Import BioBERT model and BioBERT tokenizer.
# Since the folder is in the same path of this source code, there is no need to wait for the download.
biobert=AutoModel.from_pretrained('/home/mattia/medicalSearch3.0/biobert-base-cased-v1.1')
tokenizer=BertTokenizerFast.from_pretrained('/home/mattia/medicalSearch3.0/biobert-base-cased-v1.1')

# text = np.genfromtxt(nameFileCsv, delimiter=',')

# Sample data: in our case it is the rows of the CSV.
# List of data.
with open(nameFileCsv, 'r', encoding='utf-8') as f:
    data_iter=csv.reader(f,delimiter=',')
    list_text=[data for data in data_iter]
    text=[item for sublist in list_text for item in sublist]
    # text=[_.rstrip('\n') for _ in f]

# print(text)

max_seq_len=250


# Test set.
tokens_test=tokenizer.batch_encode_plus(text, max_length=max_seq_len, padding='max_length', truncation=True, return_token_type_ids=False)

# Convert the encoded input into tensors.
unlabeled_test_seq = torch.tensor(tokens_test['input_ids'])
unlabeled_test_mask = torch.tensor(tokens_test['attention_mask'])

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
      self.fc3=nn.Linear(512,size)

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



# Load the saved weights.
path='/home/mattia/medicalSearch3.0/saved_weights.pt'
model.load_state_dict(torch.load(path))



# Apply predictions to the text to label.
with torch.no_grad():
    preds=model(unlabeled_test_seq.to(device), unlabeled_test_mask.to(device))
    print("ciao")
    preds=preds.detach().cpu().numpy()



# The labels to be applied.
preds=np.argmax(preds, axis=1)



# Write predictions in a .txt file.
f=open('/home/mattia/medicalSearch3.0/Apply/predictions.txt', 'w')
for i in preds:
    f.write(str(i)+'\n')
	    
f.close()
