@extends('layouts.window')

@section('content')

<div class="container" class="col-md-12 col-sm-12 col-xs-12">
    
   <div id="progressbar">placeholder per la barra</div>
   <button class="btn-primary">Stop</button>
  
  
  
  
  </div>
  
  <script type="text/javascript">

    $('document').ready(function(){


        // var intervalId = window.setInterval(function(){
        //     progress();
        // }, 10000);

        // clearInterval(intervalId) 


    });



    function progress(){

        $.ajax({
          url: '/getProgress',
          type: 'get',
          dataType:'json',
          
          
          success: function(response){

            console.log("chiamata get andata a buon fine valore :");


              console.log(response);

            //   document.getElementById("myButton").value=response;
            
        
            },
            error:function(response,stato){
              
              console.log(stato);
            }
      }) //fine chiamata ajax




    }


  </script>

  
@endsection