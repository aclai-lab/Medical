@extends('layouts.traininterface')

@section('content')

<div class="container" class="col-md-12 col-sm-12 col-xs-12">

  <input type="text" name="id_query" id="id_query" value="{{$id}}" hidden>
  
      <div class="info">
        <div>
        <label>Seleziona una strategia</label>
        <select>
          <option disabled selected value> -- select an option -- </option>
          <option>Random</option>
          <option>Increasing time</option>
          <option>Decreasing time</option>

        </select>
      </div>


      
        {{-- <div>
          <label>Seleziona un database</label>
          <select>
            <option disabled selected value> -- select an option -- </option>
            <option>Pubmed</option>
            <option>Scopus</option>
          </select>

          <label class="ml-2">Inserisci una Apikey per tale database</label>
          <input type="text">
          <small>Se non inserita verrà usata quella di defualt</small> 

        </div> --}}
          <p id="count"></p>

      </div>

      <div class="button-layer mt-1">


      {{-- <a href="#" class="btn btn-primary float-md-left mb-2 mr-2">Test</a>
      <a href="#" class="btn btn-primary float-md-left mb-2 ml-2 mr-2">Apply</a>  
      <a href="#" class="btn btn-primary float-md-left mb-2 ml-2 mr-2">Undo</a>  
      <a href="#" class="btn btn-primary float-md-left mb-2 ml-2 mr-2">Save labeling</a>   --}}
      <a href="{{URL::action('QueryController@index')}}" class="btn btn-secondary float-md-right mb-2">Back</a>

      </div>
  
    <div class="mt-2">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Title</th>
            <th scope="col">Abstract</th>
            <th scope="col">Year</th>            
            <th scope="col">Authors</th>
            <th scope="col">Label</th>
            <!--spazio creazione label colonne -->
            <th scope="col">Label by</th>
          </tr>
        </thead>
        <tbody>

          {{-- @foreach ($pubb as $item)
              
          
          <tr>
            <th scope="row">{{$item->title}}</th>
            <td>{{$item->abstract}}</td> 
            <td>{{$item->pubblication_year}}</td> 
            <td>
  
            @foreach($item->write as $w)
                      
                       
                @foreach($w->author as $a)
                    @if($a->web_page != null)
                      <p> <a href={{$a->web_page}}>{{$a->last_name}} {{$a->first_name}} </a><p>
                    @else
                       <p> {{$a->last_name}} {{$a->first_name}} </p> 
                    @endif  
                     

                @endforeach       

            @endforeach

            

            </td>
            <td>

              <select id="select" onchange="change(this,{{$item->pubId}})">
                <option disabled selected value> -- select an option -- </option>

                @foreach ($label as $itemLabel)
                  @if($item->id_labels == $itemLabel->id)
                    <option selected value={{$itemLabel->id}}>{{$itemLabel->name}}</option>
                  @else
                    <option value={{$itemLabel->id}}>{{$itemLabel->name}}</option>
                  @endif
                @endforeach
              </select>

            </td>
            @if($item->supervisor == null)
              <td><p id="pLabel{{$item->pubId}}"></p></td>
            @else 
              <td><p id="pLabel{{$item->pubId}}">{{$item->supervisor}}</p></td>
            @endif
          
          
          @endforeach  --}}

          @foreach ($pubb as $d)  <!-- Metodo due per la stampa degli autori neccessita del secondo pubb nel controller-->
           
              @foreach ($d->pubblication as $pb)
              {{-- {{$pb}} --}}
                  <tr>
                    <th scope="row">{{$pb->title}}</th>
                    <td>{{$pb->abstract}}</td> 
                    <td>{{$pb->pubblication_year}}</td> 
                    <td>

                      @foreach ($pb->write as $w)

                          @foreach($w->author as $a)
                        
                              @if($a->web_page != null)
                                <p> <a href={{$a->web_page}}>{{$a->last_name}} {{$a->first_name}} </a><p>
                              @else
                                <p> {{$a->last_name}} {{$a->first_name}} </p> 
                              @endif  
                      
        
                          @endforeach       
                      
                  @endforeach <!-- fine foreach per la stampa degli autori -->
    
                  <td>
                    {{-- @if(count($pb->lbl) > 0)
                        <p>yes  {{$pb->lbl}}</p>
                    @endif --}}
                  <select id="select" onchange="change(this,{{$pb->id}})">
                    <option disabled selected value> -- select an option -- </option>



{{--     
                      @foreach ($pb->lbl as $l) 
                         
                           @foreach ($label as $itemLabel)
                              @if($l->id_labels == $itemLabel->id)
                                <option selected value={{$itemLabel->id}}>{{$itemLabel->name}}</option>
                              @else
                                <option value={{$itemLabel->id}}>{{$itemLabel->name}}</option>
                              @endif
                        @endforeach    
                        
                    
                   </select>

                    @if($l->supervisor == null)
                      <td><p id="pLabel{{$pb->id}}"></p></td>
                    @else 
                      <td><p id="pLabel{{$pb->id}}">{{$l->supervisor}}</p></td>
                    @endif
    
                  @endforeach --}}


                  @foreach ($label as $itemLabel)

                        @if(count($pb->lbl) > 0)

                            @foreach ($pb->lbl as $l)

                                @if($l->id_labels == $itemLabel->id)
                                    <option selected value={{$itemLabel->id}}>{{$itemLabel->name}}</option>
                                 @else
                                    <option value={{$itemLabel->id}}>{{$itemLabel->name}}</option>
                                @endif

                            @endforeach 
                        @else 
                          
                            <option value={{$itemLabel->id}}>{{$itemLabel->name}}</option>

                        @endif

                  @endforeach  <!-- fine foreach per le label-->

                </select>

                @if(count($pb->lbl) > 0)

                    @foreach ($pb->lbl as $l)

                          <td><p id="pLabel{{$pb->id}}">{{$l->supervisor}}</p></td>

                    @endforeach 

                @else 
                
                  <td><p id="pLabel{{$pb->id}}"></p></td>

                @endif


                   
                  
              @endforeach <!-- fine foreach per la stampa delle informazioni -->

        


              
          @endforeach<!--Fine foreach ciclo dei discover -->
                
        </tbody>
      </table>








    </div>
  
  
  </div>

  <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
              Confusion Matrix
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                
                
    


                </div>
            </div>
        </div>
    </div>

  

<script>

var c =0;
// var id_pub = [];
// var id_lab = [];

$('document').ready(function(){

// getapi();



  localStorage.setItem('alerted','no');

  count();
  fetchpercent();


  // var id = document.getElementById('id').value;

  // alert(id);
});


// $('#select').change(function() {
//   alert('The option with value ' + $(this).val() + ' and text ' + $(this).text() + ' was selected.');
// });
 
/**
@brief change or create a tuple in the database

with an ajax funtion sends the parameters and sets them in the database

@param none
@return none
*/
function change(selector,id)
{
  var id_pub = id;
  var id_lab = selector.value;
  var tag = "pLabel"+id;
  var queryId = document.getElementById('id_query').value;
  var _token = "{{csrf_token()}}";
    // console.log(id);


  $.ajax({
          url: '/change',
          type: 'post',
          // dataType:'json',
          data:{'queryId':queryId,'id_pub':id_pub,'id_lab':id_lab,'_token':_token},
          
          success: function(){

           
              count();
              document.getElementById(tag).innerHTML = "User";
            
        
            },
            error:function(stato){
              
              console.log(stato);
            }
      }) //fine chiamata ajax

  // if(id_pub.length == 0)
  // {
  //   id_pub.push(id);
  //   id_lab.push(value);

  //   c++;

  //   // console.log(c);

  //   document.getElementById("labelResult").innerHTML = "Total labeling : "+c; 
  //   document.getElementById(tag).innerHTML = "User"; 
  // }
  // else
  // {
  //   var found = false;
    
  //     for(var i = 0; i < id_pub.length; i++)
  //     {
  //       if(id_pub[i] == id)
  //       {
  //         found = true;

  //         id_lab[i] = value;
        
  //         i = id_pub.length+1;
  //       }

  //     }//fine for

  //     if(found == false)
  //     {
  //         id_pub.push(id);
  //         id_lab.push(value);

  //         c++;

  //         document.getElementById("labelResult").innerHTML = "Total labeling : "+c; 
  //         document.getElementById(tag).innerHTML = "User"; 
  //     }

  // }

  


}

/**
@brief call the function test.py

with an ajax funtion call call the test.py then set the result to the respective paragraphs

@param none
@return none
*/
 function testTrain()
 {

  var _token = "{{csrf_token()}}";
  var queryId = document.getElementById('id_query').value;

  document.getElementById('train-info').style.display = "none";
  document.getElementById('spinner-train').style.display = "inline";
  document.getElementById('modal-error').style.display = "none";

  $.ajax({
          url: '/fetchtrain',
          type: 'post',
          // dataType:'json',
          data:{'queryId':queryId,'_token':_token},
          
          success: function(response){
            // console.log(response);

            if(response.includes('Accuracy'))
            {
              var tok=response.split(":");
              var accuracy = parseFloat(tok[1]);
           
              document.getElementById('accuracy').innerHTML = 'Accuracy :'+accuracy +' %';
              document.getElementById('info').innerHTML = "Accuracy obtained: " +accuracy+" %";
              document.getElementById('train-info').style.display = "inline";
              document.getElementById('spinner-train').style.display = "none";
              document.getElementById('modal-error').style.display = "none";

            }
            else
            {
              // alert('Già in esecuzione');
              document.getElementById('train-info').style.display = "none";
              document.getElementById('spinner-train').style.display = "none";
              document.getElementById('modal-error').style.display = "inline";
            }
              
            
        
            },
            error:function(response,stato){
              
              console.log(stato);
            }
      }) //fine chiamata ajax



 }
/**
@brief count the number of label set by the user

to count in the right way avoiding "repeat count" errors and without creating arrays it is easier to use a QL that counts elements

@param none
@return none
*/
 function count()
 {

  var _token = "{{csrf_token()}}";
  var queryId = document.getElementById('id_query').value;

  $.ajax({
          url: '/count',
          type: 'post',
          // dataType:'json',
          data:{'queryId':queryId,'_token':_token},
          
          success: function(response){

            // console.log(response);
            // alert("tutto bene save");
            document.getElementById("labelResult").innerHTML = "Total labeling : "+response; 
              
            
        
            },
            error:function(stato){
              
              console.log(stato);
            }
      }) //fine chiamata ajax



 }
 /**
@brief show the modal of the confusion matrix
@param none
@return none
*/
function showMatrix()
{
 

  var _token = document.getElementById('_token').value;
  var queryId = document.getElementById('id_query').value;


  $.ajax({
            url: '/showconfusionmatrix/'+queryId,
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
                // alert("Page " + href + " cannot open. Error:" + error);
                $('#loader').hide();
            },
  })
}
 /**
@brief call the funtion forgetlabel through ajax
@param none
@return none
*/
 function deleteLabel()
 {


  var _token = "{{csrf_token()}}";
  var queryId = document.getElementById('id_query').value;
  
// document.getElementById('train-info').style.display = "none";
// document.getElementById('spinner-train').style.display = "inline";
// document.getElementById('modal-error').style.display = "none";

  $.ajax({
        url: '/forgetlabel',
        type: 'post',
        // dataType:'json',
        data:{'queryId':queryId,'_token':_token},
        
        success: function(){

          // console.log(response);
          // alert("tutto bene delete");
          location.reload(); 

      
          },
          error:function(stato){
            
            console.log(stato);
          }
    }) //fine chiamata ajax

 }
 
 /**
@brief call the funtion undo through ajax
@param none
@return none
*/
 function undo()
 {


  var _token = "{{csrf_token()}}";
  var queryId = document.getElementById('id_query').value;

// document.getElementById('train-info').style.display = "none";
// document.getElementById('spinner-train').style.display = "inline";
// document.getElementById('modal-error').style.display = "none";

  $.ajax({
        url: '/undolabel',
        type: 'post',
        // dataType:'json',
        data:{'queryId':queryId,'_token':_token},
        
        success: function(){

          // console.log(response);
          // alert("tutto bene undo");

      
          },
          error:function(stato){
            
            console.log(stato);
          }
    }) //fine chiamata ajax

 }
 /**
@brief call the funtion apply through ajax
@param none
@return none
*/
 function apply()
 {


  var _token = "{{csrf_token()}}";
  var queryId = document.getElementById('id_query').value;

  document.getElementById('apply-info').style.display = "none";
  document.getElementById('spinner-apply').style.display = "inline";
  document.getElementById('apply-modal-error').style.display = "none";

  $.ajax({
        url: '/apply',
        type: 'post',
        // dataType:'json',
        data:{'queryId':queryId,'_token':_token},
        
        success: function(response){

          // console.log(response);

          if(response.includes('Ok'))
          {      
            location.reload();
          }
          else
          {
              document.getElementById('apply-info').style.display = "none";
              document.getElementById('spinner-apply').style.display = "none";
              document.getElementById('apply-modal-error').style.display = "inline";
          }
  
          },
          error:function(stato){
            
            console.log(stato);
          }
    }) //fine chiamata ajax

 }
/**
@brief retrive the accuracy of the test
@param none
@return none
*/
 function fetchpercent()
 {


  var _token = "{{csrf_token()}}";
  var queryId = document.getElementById('id_query').value;

  $.ajax({
        url: '/fetchpercent',
        type: 'post',
        // dataType:'json',
        data:{'queryId':queryId,'_token':_token},
        
        success: function(response){

          console.log(response);
          document.getElementById('accuracy').innerHTML = "Accuracy : " +response+" %";
          // console.log("h yeas");
      
          },
          error:function(stato){
            
            console.log(stato);
          }
    }) //fine chiamata ajax

 }
 


  </script>

  
@endsection 


