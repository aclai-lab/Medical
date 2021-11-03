@extends('layouts.app')

@section('content')
{{-- 
<style>
  .container {
    width: 1200px !important;
  }

  </style> --}}

  
  

<div class="container">


    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <div class=" col-md-12 col-sm-12 col-xs-12">
        <div>
            <label>Choose type of analysis</label>
        </div>  
 
          <a href="{{URL::action('QueryController@index')}}" class="btn btn-secondary float-right ml-2 mb-2">Back</a>
    </div>

    <form action="{{URL::action('QueryController@result')}}" method="POST">
        {{csrf_field()}}
        <input type="text" id="_token" value="{{csrf_token()}}" hidden>
        <input type="text" name="id_query" id="id_query" value="{{$id}}" hidden>
        <input type="text" name="type-of" id="type-of" value="" hidden>


    <div class="statistics"  id="statistics">
        <div class="my-2 mx-1">
            <div class="option-statistics">
                <label>Select Type</label>
                <select id="statistics-type" name="statistics-type" onchange="change(this)">
                    <option disabled selected value> -- select an option -- </option>
                    <option value="geo">Geographical origin</option>
                    <option value="time">Time</option>
                    <option value="venue">Venue</option>
                    <option value="author">Author</option>
                </select>
            </div>

        </div>

        <div class="time" style="display: none" id="time_input">
            <div class="my-2 mx-1">
                <div class="time_input_data">
                    <label>Start</label>
                    <input type="text" name="start-time" id="start-time" value="">

                    <label class="mx-2">End</label>
                    <input type="text" name="end-time" id="end-time" value="">
                </div>
            </div>
        </div>

        <div class="geo" style="display: none" id="geo_input">
            <div class="my-2 mx-1">
                <div class="time_input_data">
                    <label>Start</label>
                    <input type="text" name="start-time-geo" id="start-time-geo" value="">

                    <label class="mx-2">End</label>
                    <input type="text" name="end-time-geo" id="end-time-geo" value="">
                </div>
            </div>
        </div>

        <div class="venue" style="display: none" id="venue_input">
            <div class="my-2 mx-1">
                <div class="time_input_data">
                    <label>Start</label>
                    <input type="text" name="start-time-venue" id="start-time-venue" value="">

                    <label class="mx-2">End</label>
                    <input type="text" name="end-time-venue" id="end-time-venue" value="">
                </div>
            </div>
        </div>

        <div class="author" style="display: none" id="author_input">
            <div class="my-2 mx-1">
                <div class="time_input_data">
                    <label>Start</label>
                    <input type="text" name="start-time-author" id="start-time-author" value="">

                    <label class="mx-2">End</label>
                    <input type="text" name="end-time-author" id="end-time-author" value="">
                </div>
            </div>
        </div>

   
    </div>

    <div class="submit" style="display: none" id="submit">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </div>

    </form>

</div> 








<script type="text/javascript">
/**
    not implemented anymore
*/
 function stati()
 {
    document.getElementById('statistics').style.display = "inline";
    document.getElementById('author').style.display = "none";
    document.getElementById('submit').style.display = "inline";

 } 
/**
    not implemented anymore
*/
 function analis()
 {
    document.getElementById('statistics').style.display = "none";
    document.getElementById('author').style.display = "inline";
    document.getElementById('submit').style.display = "inline";
    
 }    
/**
    @brief change page input 

    in base of the select value the script will show different input 
    for example with the value "geo" will show the text input "geo-start" and "geo-end"

    @param selector
    @return none
*/
 function change(selector)
 {
    var val = selector.value;

    document.getElementById('type-of').value = val;

    switch(val)
    {
        case "time":
            //nascondere gli altri valori
            document.getElementById('time_input').style.display = "inline";
            document.getElementById('geo_input').style.display = "none";
            document.getElementById('author_input').style.display = "none";
            document.getElementById('venue_input').style.display = "none";
            document.getElementById('submit').style.display = "inline";
            break;

        case "geo":
            //nascondere gli altri valori
            document.getElementById('geo_input').style.display = "inline";
            document.getElementById('time_input').style.display = "none";
            document.getElementById('author_input').style.display = "none";
            document.getElementById('venue_input').style.display = "none";
            document.getElementById('submit').style.display = "inline";
        break;

        case "author":
            //nascondere gli altri valori
            document.getElementById('author_input').style.display = "inline";
            document.getElementById('geo_input').style.display = "none";
            document.getElementById('time_input').style.display = "none";
            document.getElementById('venue_input').style.display = "none";
            document.getElementById('submit').style.display = "inline";
        break;

        case "venue":
            //nascondere gli altri valori
            document.getElementById('venue_input').style.display = "inline";
            document.getElementById('geo_input').style.display = "none";
            document.getElementById('author_input').style.display = "none";
            document.getElementById('time_input').style.display = "none";
            document.getElementById('submit').style.display = "inline";
        break;


    }

    // alert(document.getElementById('type-of').value);
 }  

/**
    not implemented anymore
*/
 function select(selector)
 {
    var val = selector.value;

    // document.getElementById('type-of').value = val;

    switch(val)
    {
        case "sta":
            document.getElementById('statistics').style.display = "inline";
            document.getElementById('author').style.display = "none";
            document.getElementById('submit').style.display = "inline";
                    break;
        case "aut":
            document.getElementById('statistics').style.display = "none";
            document.getElementById('author').style.display = "inline";
            document.getElementById('submit').style.display = "inline";
            break;




    }

    // alert(document.getElementById('type-of').value);
 }  






</script>

  
@endsection