@extends('layouts.app')

@section('content')

<style>


.textarea-container {
  position: relative;
  width: 100%;
}

textarea, .textarea-size {
  min-height: 25px;
  font-family: sans-serif;
  font-size: 14px;
  box-sizing: border-box;
  padding: 4px;
  /* border: 1px solid; */
  overflow: hidden;
  width: 100%;
}

textarea {
  height: 100%;
  position: absolute;
  resize:none;
  white-space: normal;
}

.textarea-size {
  visibility: hidden;
  white-space: pre-wrap;
  word-wrap: break-word;
  overflow-wrap: break-word;
}



</style>



<div class="container" class="col-md-12 col-sm-12 col-xs-12">

   @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{URL::action('QueryController@store')}}" method="POST">
    {{csrf_field()}}

  <div class="form">

        <a href="{{URL::action('QueryController@index')}}" class="btn btn-secondary float-right ml-2 mb-2">Back</a>
        <button type="submit" class="btn btn-primary float-right">Save</button>
       

        {{-- <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"> --}}



    <div class="form-group">
      <label for="name">Project Name</label>
      <input type="text" class="form-control" name="name">
      {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
    </div>

    {{-- <div class="form-group">
        <label for="description">Long Description</label>
        <input type="text" class="form-control" name="description_long">
         <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> 
    </div> --}}

    <label for="description">Long Description</label>
    <div class="form-group textarea-container">
       
      
        
    <textarea name="description_long"></textarea>
    <div class="textarea-size" name="description_long"></div>
        {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
    </div>

    <div class="form-group">
        <label for="description">Short Description</label>
        <input type="text" class="form-control" name="description_short">
        {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
    </div>

    <div class="form-group">
        <div>
            <label for="label_string">Label</label>
        </div>
        <input type="text" class="col-md-11 " id="label_string" name="label_string">
        <button class="btn btn-secondary ml-2" type="button" id="addLabel" onclick="addlabel()">ADD</button>
        <div class="mt-2 mb-2">
            <textarea id="stringlabel" class="query-box-input col-md-11" name="label" placeholder="Enter / edit your label here"></textarea>
            <a href="#" class="clear-label-btn" title="Clear query box" id="clear-query-btn" onclick="deleteLabel()"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eraser" viewBox="0 0 16 16">
                <path d="M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828l6.879-6.879zm2.121.707a1 1 0 0 0-1.414 0L4.16 7.547l5.293 5.293 4.633-4.633a1 1 0 0 0 0-1.414l-3.879-3.879zM8.746 13.547L3.453 8.254 1.914 9.793a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293l.16-.16z"/>
            </svg></a>
      
        </div>
        {{-- <small id="emailHelp" class="form-text text-muted">Seperate the labels with ";"</small> --}}
    </div>

    {{-- <div class="form-group">
        <label for="description">ApiKey</label>
        <input type="text" class="form-control" name="apikey">
    </div> --}}

      {{-- <input type="hidden" name="users_id" value="{{Auth::user()->id}}"/> questo potrebbe servirmi quando l'utente semplice mettera dati--}}

      <div class="option-db">
        <label for="Seleziona Database">Select Database</label>
        <select id="database">
            <option value="PubMed" selected>PubMed</option>
            <option value="Scopus">Scopus</option>
        </select>
            {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
    </div>
   
</div> 

<div class="border border-dark my-2">

    <div class="pubmedQuery  " style="display: inline" id="PubmedQuery">

        <div class="my-2 mx-1">
                <label>Add terms to the query box </label>
                <select id="field-selector" class="adv-search-field field-selector">
                    <option value="Affiliation">Affiliation</option><option value="All Fields" selected="selected">All Fields</option><option value="Author">Author</option><option value="Author - Corporate">Author - Corporate</option><option value="Author - First">Author - First</option><option value="Author - Identifier">Author - Identifier</option><option value="Author - Last">Author - Last</option><option value="Book">Book</option><option value="Conflict of Interest Statements">Conflict of Interest Statements</option><option value="Date - Completion">Date - Completion</option><option value="Date - Create">Date - Create</option><option value="Date - Entry">Date - Entry</option><option value="Date - MeSH">Date - MeSH</option><option value="Date - Modification">Date - Modification</option><option value="Date - Publication">Date - Publication</option><option value="EC/RN Number">EC/RN Number</option><option value="Editor">Editor</option><option value="Filter">Filter</option><option value="Grant Number">Grant Number</option><option value="ISBN">ISBN</option><option value="Investigator">Investigator</option><option value="Issue">Issue</option><option value="Journal">Journal</option><option value="Language">Language</option><option value="Location ID">Location ID</option><option value="MeSH Major Topic">MeSH Major Topic</option><option value="MeSH Subheading">MeSH Subheading</option><option value="MeSH Terms">MeSH Terms</option><option value="Other Term">Other Term</option><option value="Pagination">Pagination</option><option value="Pharmacological Action">Pharmacological Action</option><option value="Publication Type">Publication Type</option><option value="Publisher">Publisher</option><option value="Secondary Source ID">Secondary Source ID</option><option value="Subject - Personal Name">Subject - Personal Name</option><option value="Supplementary Concept">Supplementary Concept</option><option value="Text Word">Text Word</option><option value="Title">Title</option><option value="Title/Abstract">Title/Abstract</option><option value="Transliterated Title">Transliterated Title</option><option value="Volume">Volume</option></select>
                    <input type="text" class="col-md-6 ml-2" name="searchTerm" id="searchTerm" placeholder="Enter a search term" style="display: inline">
                    <input type="text" class="col-md-3 ml-2" name="date_start" id="date_start" placeholder="YYYY/MM/DD" style="display: none;">
                    <input type="text" class="col-md-3 ml-2" name="date_end" id="date_end" placeholder="Present" style="display: none;">
            
                    <button class="btn btn-secondary dropdown-toggle  float-md-right mb-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ADD
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" onclick="addAnd()">Add with AND</a>
                    <a class="dropdown-item" href="#" onclick="addOr()">Add with OR</a>
                    <a class="dropdown-item" href="#" onclick="addNot()">Add with NOT</a>
                    </div>
                
    
    
        </div>

        <div class="queryBox"><!--div del quadrato -->
            <div class="section-label ml-2">
                Query box
            </div>

            <div class="query-box-wrapper mt-2 ml-2 mb-2">
                <textarea id="string" class="query-box-input col-md-11" name="string" placeholder="Enter / edit your search query here"></textarea>
                <a href="#" class="clear-query-btn" title="Clear query box" id="clear-query-btn" onclick="deleteQuery()"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eraser" viewBox="0 0 16 16">
                    <path d="M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828l6.879-6.879zm2.121.707a1 1 0 0 0-1.414 0L4.16 7.547l5.293 5.293 4.633-4.633a1 1 0 0 0 0-1.414l-3.879-3.879zM8.746 13.547L3.453 8.254 1.914 9.793a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293l.16-.16z"/>
                </svg></a>
            </div>

        </div>
            
    </div>

    <div class="Scopus" style="display: none;" id="ScopusQuery"><!--Scopus Part-->

    <div class="my-2 ">
        <select id="filedScopus" class="adv-search-field field-selector">
            <option value="Title" selected="selected">Title</option>
            <option value="Publisher">Publisher</option>
            <option value="AuthorSearch">Author Search</option>
            <option value="SubjectArea">Subject Area</option>
            <option value="ISSN">ISSN</option>
        </select>
    </div>

        <div class="Title" style="display: inline;" id="title">

            <label>Inserisi un titolo</label>   
            <input type="text" class="col-md-6 ml-2" name="searchTerm" id="searchTerm" placeholder="Inserisci un termine di ricerca" style="display: inline">
                    
        </div>

        <div class="Publisher" style="display: none;" id="publisher">
            
            <label>Inserisi un publisher</label>   
            <input type="text" class="col-md-6 ml-2" name="searchTerm" id="searchTerm" placeholder="Inserisci un termine di ricerca" style="display: inline">
                    
        </div>

        <div class="issn" style="display: none;" id="issn">
            
            <label>Inserisi un issn</label>   
            <input type="text" class="col-md-6 ml-2" name="searchTerm" id="searchTerm" placeholder="Inserisci un termine di ricerca" style="display: inline">
                
        </div>

        <div class="SubjetArea" style="display: none;" id="subjectarea">
            
            <label>checkbox</label>
            
        </div>

        <div class="AuthorSearch" style="display: none;" id="authorsearch">

            <label>Ricerca per autori</label>
            
                <input type="text" class="col-md-6 ml-2" name="searchTerm" id="searchTerm" placeholder="Inserisci un termine di ricerca" style="display: inline">
                <input type="text" class="col-md-6 ml-2" name="searchTerm" id="searchTerm" placeholder="Inserisci un termine di ricerca" style="display: inline">
                <input type="text" class="col-md-6 ml-2" name="searchTerm" id="searchTerm" placeholder="Inserisci un termine di ricerca" style="display: inline">


            
                <input type="text" class="col-md-6 ml-2" name="searchTerm" id="searchTerm" placeholder="Inserisci un termine di ricerca" style="display: inline">


        </div>
        
    
        
        
    </div>

</div>


<div>{{-- getPreview() --}}
    {{-- <button class="btn btn-outline-secondary" type="button" id="previe-btn" onclick="getPreview()">Preview</button> --}}

    <a data-toggle="modal" id="smallButton" data-target="#mediumModal" type="button" class="btn btn-outline-secondary" onclick="getPreview()" title="Preview">
    Preview
    </a>

    <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
              Preview of your query
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                
                
    


                </div>
            </div>
        </div>
    </div>
</div>


</div>
</form>

 
  
  
  
</div><!-- chiusura div principale -->
  
<script>
/**
@brief change the text boxe for the scopus input



@param none
@return none
*/
$('#filedScopus').change(function(e){

    // console.log(document.getElementById('filedScopus').value);


    if(document.getElementById('filedScopus').value == "Title")
    {
        document.getElementById('title').style.display="inline";
        document.getElementById('publisher').style.display="none";
        document.getElementById('issn').style.display="none";
        document.getElementById('authorsearch').style.display="none";
        document.getElementById('subjectarea').style.display="none";
    }
    else if(document.getElementById('filedScopus').value == "Publisher")
    {
        document.getElementById('title').style.display="none";
        document.getElementById('publisher').style.display="inline";
        document.getElementById('issn').style.display="none";
        document.getElementById('authorsearch').style.display="none";
        document.getElementById('subjectarea').style.display="none";
    }
    else if(document.getElementById('filedScopus').value == "ISSN")
    {
        document.getElementById('title').style.display="none";
        document.getElementById('publisher').style.display="none";
        document.getElementById('issn').style.display="inline";
        document.getElementById('authorsearch').style.display="none";
        document.getElementById('subjectarea').style.display="none";
    }
    else if(document.getElementById('filedScopus').value == "AuthorSearch")
    {
        document.getElementById('title').style.display="none";
        document.getElementById('publisher').style.display="none";
        document.getElementById('issn').style.display="none";
        document.getElementById('authorsearch').style.display="inline";
        document.getElementById('subjectarea').style.display="none";
    }
    else
    {
        document.getElementById('title').style.display="none";
        document.getElementById('publisher').style.display="none";
        document.getElementById('issn').style.display="none";
        document.getElementById('authorsearch').style.display="none";
        document.getElementById('subjectarea').style.display="inline";

    }
});


/**
@brief show the div content for scopus or Pubmed

automatically when the selector is changed the contents of the corresponding div will be shown,
by default the page will be set to pubmed.
for example setting the value to scopus will set div scopus to inline that will show it 
and will hide div of pumed 

@param none
@return none
*/
$('#database').change(function(e){


    if(document.getElementById('database').value == "Scopus")
    {
        document.getElementById('PubmedQuery').style.display="none";
        document.getElementById('ScopusQuery').style.display="inline";
    }
    else
    {
        document.getElementById('ScopusQuery').style.display="none";
        document.getElementById('PubmedQuery').style.display="inline";
    }
});

/**
@brief change the type of input

automatically when the selector is changed the contents of the corresponding input will be shown,
by default is set to text,the two types of useless are text and date.
the dates must have the input regarding the start and the end,if the selector contains date it will change by hiding the "searhcTeram" input and show the dates
 

@param none
@return none
*/
$('#field-selector').change(function(e){

    e.preventDefault();
    var selectedValue = $("#field-selector").val();

    if(selectedValue.includes("Date")){
        // console.log("funziona sono in Date");

        document.getElementById('searchTerm').style.display="none";
        document.getElementById('date_start').style.display="inline";
        document.getElementById('date_end').style.display="inline";


    }
    else
    {
        // console.log("funziona sono in Else");

        document.getElementById('searchTerm').style.display="inline";
        document.getElementById('date_start').style.display="none";
        document.getElementById('date_end').style.display="none";

    }

  
  
});
/**
@brief delete the label
 
@param none
@return none
*/
function deleteLabel(){


    document.getElementById('stringlabel').value = '';


}
/**
@brief add part of the label
 
    add part of the label for the classification with the separator ";"

@param none
@return none
*/
function addlabel(){

    var label = $("#label_string").val();

    if(label === ""){
        alert("il campo label è vuoto");
        document.getElementById('label_string').focus();
    }
    else
    {
        var textLabel = $('#stringlabel').val();

        if(textLabel === "" )
        {
                document.getElementById('stringlabel').value = label+";";
        }
        else
        {
                document.getElementById('stringlabel').value = textLabel + label + ";";
        }

        document.getElementById('label_string').value = '';
    }
}

/**
@brief add the the search term with and
 
    first a check is made to distinguish the date from simple texts,
     in case it is a date we have two inputs a start and an end in case the dates are not put default values ​​will be set, 
     once these checks are finished the value is taken del select and is inserted in [] in case that a query string is already present the word and is also added to connect it

@param none
@return none
*/
function addAnd() {

  var selectedValue = $("#field-selector").val();

  if(selectedValue.includes("Date"))
  {
      var date_start=$('#date_start').val();
      var date_end=$('#date_end').val();

      if(date_start === "" ){
            alert("il campo data d'inizio è vuota");
            document.getElementById('date_start').focus();
     }

     if(date_end === "" ){

            date_end = "3000";
    }

    if(date_start.localeCompare(date_end) != -1){
            alert("la data fine precede quella di inizio");
            document.getElementById('date_end').focus();
            
    }
    else
    {

        var textQuery = $('#string').val();

        if(textQuery === "" )
        {
            document.getElementById('string').value = "\""+date_start+"\"" + "[" + selectedValue + "]" + ":" +"\""+date_end +"\"["+selectedValue+"]";
        }
        else
        {
            document.getElementById('string').value = textQuery + " AND " + "\""+date_start+"\"" + "[" + selectedValue + "]" + ":" +"\""+date_end +"\"["+selectedValue+"]";
        }


        document.getElementById('date_start').value = '';
        document.getElementById('date_end').value = '';
    }

  }
  else if(selectedValue.localeCompare("All Fields"))
  {
        var text=$('#searchTerm').val();

        if(text === "" ){
            alert("il campo di ricerca è vuoto");
            document.getElementById('searchTerm').focus();
        }
        


        var textQuery = $('#string').val();

        if(textQuery === "" )
        {
            document.getElementById('string').value = text + "[" + selectedValue + "]";
        }
        else
        {
            document.getElementById('string').value = textQuery + " AND " + text + "[" +selectedValue +"]";
        }

       document.getElementById('searchTerm').value = '';

 }
 else
 {

    var text=$('#searchTerm').val();

    if(text === "" ){
        alert("il campo di ricerca è vuoto");
        document.getElementById('searchTerm').focus();
    }



    var textQuery = $('#string').val();

    if(textQuery === "" )
    {
        document.getElementById('string').value = text;
    }
    else
    {
        document.getElementById('string').value = textQuery + " AND " + text;
    }

    document.getElementById('searchTerm').value = '';

 }


}//fine funzione AND

/**
@brief add the the search term with OR
 
    first a check is made to distinguish the date from simple texts,
     in case it is a date we have two inputs a start and an end in case the dates are not put default values ​​will be set, 
     once these checks are finished the value is taken del select and is inserted in [] in case that a query string is already present the word or is also added to connect it

@param none
@return none
*/
function addOr() {
    var selectedValue = $("#field-selector").val();

  if(selectedValue.includes("Date"))
  {
      var date_start=$('#date_start').val();
      var date_end=$('#date_end').val();

      if(date_start === "" ){
            alert("il campo data d'inizio è vuota");
            document.getElementById('date_start').focus();
     }

     if(date_end === "" ){

            date_end = "3000";
    }

    if(date_start.localeCompare(date_end) != -1){
            alert("la data fine precede quella di inizio");
            document.getElementById('date_end').focus();
            
    }
    else
    {

        var textQuery = $('#string').val();

        if(textQuery === "" )
        {
            document.getElementById('string').value = "\""+date_start+"\"" + "[" + selectedValue + "]" + ":" +"\""+date_end +"\"["+selectedValue+"]";
        }
        else
        {
            document.getElementById('string').value = textQuery + " OR " + "\""+date_start+"\"" + "[" + selectedValue + "]" + ":" +"\""+date_end +"\"["+selectedValue+"]";
        }

        document.getElementById('date_start').value = '';
        document.getElementById('date_end').value = '';

    }

  }
  else if(selectedValue.localeCompare("All Fields"))
  {
        var text=$('#searchTerm').val();

        if(text === "" ){
            alert("il campo di ricerca è vuoto");
            document.getElementById('searchTerm').focus();
        }
        


        var textQuery = $('#string').val();

        if(textQuery === "" )
        {
            document.getElementById('string').value = text + "[" + selectedValue + "]";
        }
        else
        {
            document.getElementById('string').value = textQuery + " OR " + text + "[" +selectedValue +"]";
        }

        document.getElementById('searchTerm').value = '';
       

 }
 else
 {

    var text=$('#searchTerm').val();

    if(text === "" ){
        alert("il campo di ricerca è vuoto");
        document.getElementById('searchTerm').focus();
    }



    var textQuery = $('#string').val();

    if(textQuery === "" )
    {
        document.getElementById('string').value = text;
    }
    else
    {
        document.getElementById('string').value = textQuery + " OR " + text;
    }

    document.getElementById('searchTerm').value = '';

 }
}//fine funzione OR

/**
@brief add the the search term with not
 
    first a check is made to distinguish the date from simple texts,
     in case it is a date we have two inputs a start and an end in case the dates are not put default values ​​will be set, 
     once these checks are finished the value is taken del select and is inserted in [] in case that a query string is already present the word not is also added to connect it

@param none
@return none
*/
function addNot() {
    var selectedValue = $("#field-selector").val();

    if(selectedValue.includes("Date"))
    {
        var date_start=$('#date_start').val();
        var date_end=$('#date_end').val();

        if(date_start === "" ){
            alert("il campo data d'inizio è vuota");
            document.getElementById('date_start').focus();
    }

    if(date_end === "" ){

            date_end = "3000";
    }

    if(date_start.localeCompare(date_end) != -1){
            alert("la data fine precede quella di inizio");
            document.getElementById('date_end').focus();
            
    }
    else
    {

        var textQuery = $('#string').val();

        if(textQuery === "" )
        {
            document.getElementById('string').value = "\""+date_start+"\"" + "[" + selectedValue + "]" + ":" +"\""+date_end +"\"["+selectedValue+"]";
        }
        else
        {
            document.getElementById('string').value = textQuery + " NOT " + "\""+date_start+"\"" + "[" + selectedValue + "]" + ":" +"\""+date_end +"\"["+selectedValue+"]";
        }

        document.getElementById('date_start').value = '';
        document.getElementById('date_end').value = '';

    }

    }
    else if(selectedValue.localeCompare("All Fields"))
    {
        var text=$('#searchTerm').val();

        if(text === "" ){
            alert("il campo di ricerca è vuoto");
            document.getElementById('searchTerm').focus();
        }
        


        var textQuery = $('#string').val();

        if(textQuery === "" )
        {
            document.getElementById('string').value = text + "[" + selectedValue + "]";
        }
        else
        {
            document.getElementById('string').value = textQuery + " NOT " + text + "[" +selectedValue +"]";
        }

        document.getElementById('searchTerm').value = '';
        

    }
    else
    {

    var text=$('#searchTerm').val();

    if(text === "" ){
        alert("il campo di ricerca è vuoto");
        document.getElementById('searchTerm').focus();
    }



    var textQuery = $('#string').val();

    if(textQuery === "" )
    {
        document.getElementById('string').value = text;
    }
    else
    {
        document.getElementById('string').value = textQuery + " NOT " + text;
    }

    document.getElementById('searchTerm').value = '';

    }
 
}//fine funzione not

/**
    @brief delete all the query string

    @param none
    @return none
*/
function deleteQuery(){
    document.getElementById('string').value = '';
}


var textContainer, textareaSize, input;
var autoSize = function () {
  textareaSize.innerHTML = input.value + '\n';
};

  document.addEventListener('DOMContentLoaded', function() {
  textContainer = document.querySelector('.textarea-container');
  textareaSize = textContainer.querySelector('.textarea-size');
  input = textContainer.querySelector('textarea');
  
  autoSize();
  input.addEventListener('input', autoSize);
});
/**
    @brief hide the delete button when the query string is empty

    @param none
    @return none
*/
function hiddenBtn(){

    var string = document.getElementById('string').value;

    if(string == null)
        document.getElementById('smallButton').style.display="none";
    else
        document.getElementById('smallButton').style.display="inline";

}
/**
    @brief fetch the first 20 pubblications of the string

    call the ajax functon preview

    @param none
    @return none
*/
function getPreview(){

    var string = document.getElementById('string').value;
    var _token = document.getElementById('_token').value;

    // var btn=document.getElementById('smallButton');

    // var route = btn.getAttribute('data-attr');

    var route = '/preview';
    href=route+'/'+string;

    // console.log(href);

            $.ajax({
                url: href,
                // type:'post',
                // data: {'string' : string,'_token':_token},

                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#mediumModal').modal("show");
                    $('#mediumBody').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
}


</script>
  
@endsection

