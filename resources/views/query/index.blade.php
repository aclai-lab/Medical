@extends('layouts.app')

@section('content')
{{-- 
<style>
  .container {
    width: 1200px !important;
  }

  </style> --}}

  {{csrf_field()}}
  <input type="text" id="_token" value="{{csrf_token()}}" hidden>

<div class="container">
<div class=" col-md-12 col-sm-12 col-xs-12">
            <a href="{{URL::action('QueryController@create')}}" class="btn btn-primary float-md-right mb-2">New</a>
            <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Total Result</th>
                    <th scope="col">Accuracy</th>
                    <th scope="col">Creation Date</th>
                    <th scope="col">Last execution date</th>
                    <th scope="col">Label</th>
                    <th scope="col"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                   
                  </tr>
                </thead>
                <tbody>

                    @foreach ($q as $item)
                        
                    
                    <tr>
                      <th scope="row">{{$item->name}}</th>
                      @if($item->description_short != null)
                          <td>{{$item->description_short}}</td>
                      @else
                          <td>no description provided</td>
                      @endif
                    
                      <td>{{$item->pre_exc}}</td> 
                      @if($item->accuracy != null)
                          <td>{{$item->accuracy}} %</td>
                      @else
                          <td>never performed</td>
                      @endif
                      <td>{{date('d/m/Y',strtotime($item->created_at))}}</td>

                      @if($item->latest_exc_date != null)
                        <td>{{date('d/m/Y',strtotime($item->latest_exc_date))}}</td>
                      @else
                        <td>never performed</td>
                      @endif
                  
                      {{-- @foreach ($item as $lab)


                      @endforeach --}}
                      <td>{{$item->label}}</td>

                      
                      {{-- <td><input type="radio" id="project" name="project[]" onclick="radioClick({{$item}})" value="{{$item}}">  </td> <a href="{{URL::action('QueryController@train',$item->id)}}" class="btn btn-outline-primary btn-sm" disabled>Train</a>--}}
                      <td>   <a data-toggle="modal" data-target="#mediumModalExecute" type="button" class="btn btn-outline-primary btn-sm" data-attr="{{$item->id}}"  onclick="executeChoice({{$item->id}})" title="Choose">
                        Execute
                        </a></td>

                      @if($item->ret_start == 0)
                        <td><button type="button" class="btn btn-outline-primary btn-sm" disabled>Train</button></td>
                      @else
                        <td><a href="{{URL::action('QueryController@train',$item->id)}}" class="btn btn-outline-primary btn-sm">Train</a></td>
                      @endif
                      {{-- <a href="{{URL::action('QueryController@execute',$item)}}" class="btn btn-outline-secondary btn-sm">Execute</a>

                      <td>   <a data-toggle="modal" id="smallButtonExecute" data-target="#mediumModalExecute" type="button" class="btn btn-outline-secondary btn-sm" data-attr="{{$item->id}}" onclick="executeChoice()" title="Choose">
                        Execute
                        </a></td> --}}
                      
                      @if($item->latest_exc_date == null)
                        <td><button type="button" class="btn btn-outline-primary btn-sm" disabled>Analysis</button></td>
                      @else
                        <td><a href="{{URL::action('QueryController@build',$item->id)}}" class="btn btn-outline-primary btn-sm">Analysis</a></td>
                      @endif

                      {{-- @if($item->ret_start != 0)
                        <td><a href="{{URL::action('QueryController@execute',$item)}}" class="btn btn-outline-secondary btn-sm">Execute From Last Stop</a></td>
                      @else
                        <td><a href="{{URL::action('QueryController@execute',$item)}}" class="btn btn-outline-secondary btn-sm disabled">From Stop</a></td>
                      @endif --}}
                    <td><a href="{{URL::action('QueryController@edit',$item)}}" class="btn btn-outline-secondary btn-sm">Modify</a></td> 

                      <td><a href="{{URL::action('QueryController@destroy',$item->id)}}" class="btn btn-outline-danger btn-sm">Delete</a></td>
                    </tr>
                    
                    @endforeach 
                        
      
                </tbody>
              </table>

  
  </div>
</div> 


{{-- <div class="modal fade" id="mediumModalExecute" tabindex="-1" role="dialog" aria-labelledby="mediumModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            Choose the type of execute
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body" id="mediumBodyExecute">
          </div>
        </div>
    </div>
  </div>
</div> --}}


<div class="modal" tabindex="-1" role="dialog" id="mediumModalExecute">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Choose the type of execute</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="mediumBodyExecute">
          <div class="button-modal" style="display: inline" id="button-modal">
            <label>Execute at to from:</label>
            <select id="optionFromStart">
                <option value="10%" >10%</option><option value="20%">20%</option><option value="30%">30%</option><option value="40%">40%</option><option value="50%">50%</option><option value="60%">60%</option><option value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeStart()">Execute</button>
          </div>

          <div></div>

          <div class="button-modal-10" style="display: none;" id="button-modal-from-10">
            <label>Resume from last:</label>
            <select id="optionFromLast10">
                <option selected value="20%">20%</option><option value="30%">30%</option><option value="40%">40%</option><option value="50%">50%</option><option value="60%">60%</option><option value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast10').value)">Execute</button>
          </div>

          <div class="button-modal-20" style="display: none;" id="button-modal-from-20">
            <label>Resume from last:</label>
            <select id="optionFromLast20">
              <option selected value="30%">30%</option><option value="40%">40%</option><option value="50%">50%</option><option value="60%">60%</option><option value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast20').value)">Execute</button>
          </div>

          <div class="button-modal-30" style="display: none;" id="button-modal-from-30">
            <label>Resume from last:</label>
            <select id="optionFromLast30">
              <option selected value="40%">40%</option><option value="50%">50%</option><option value="60%">60%</option><option value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast()document.getElementById('optionFromLast30').value">Execute</button>
          </div>

          <div class="button-modal-40" style="display: none;" id="button-modal-from-40">
            <label>Resume from last:</label>
            <select id="optionFromLast40">
             <option selected value="50%">50%</option><option value="60%">60%</option><option value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast40').value)">Execute</button>
          </div>

          <div class="button-modal-50" style="display: none;" id="button-modal-from-50">
            <label>Resume from last:</label>
            <select id="optionFromLast50">
             <option selected value="60%">60%</option><option value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast50').value)">Execute</button>
          </div>

          <div class="button-modal-60" style="display: none;" id="button-modal-from-60">
            <label>Resume from last:</label>
            <select id="optionFromLast60">
              <option selected value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast60').value)">Execute</button>
          </div>

          <div class="button-modal-70" style="display: none;" id="button-modal-from-70">
            <label>Resume from last:</label>
            <select id="optionFromLast70">
             <option selected value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast70').value)">Execute</button>
          </div>


          <div class="button-modal-80" style="display: none;" id="button-modal-from-80">
            <label>Resume from last:</label>
            <select id="optionFromLast80">
            <option selected value="90%">90%</option><option value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast80').value)">Execute</button>
          </div>

          <div class="button-modal-90" style="display: none;" id="button-modal-from-90">
            <label>Resume from last:</label>
            <select id="optionFromLast90">
             <option selected value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast90').value)">Execute</button>
          </div>

          <div class="button-modal-100" style="display: none;" id="button-modal-from-100">
            <label>Resume from last:</label>
            <select id="optionFromLast100">
             <option selected value="100%">100%</option>
            </select>
            <button class="btn btn-outline-primary btn-sm float-md-right" onclick="executeFromLast(document.getElementById('optionFromLast100').value)" disabled>Execute</button>
          </div>

          {{--qui --}}
        
        <div class="text-center" id="spinner" style="display: none;">
         <div class="d-flex justify-content-center" >
          <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>

      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}
    </div>
  </div>
</div>




  <script type="text/javascript">

  $('document').ready(function(){


    // document.getElementById("Hpositionp").innerHTML = "Current Position : All Projects";





  });

/**
@brief start the retrive of the pubblications from the start 

as long as the recovery is in progress the modal will show a "spinner" and rimmarerà until the withdrawal ends

@param none
@return none
*/
  function executeStart()
    {
        // var id = document.getElementById("inputID").value;
        var id = localStorage.getItem('queryID');
        var percent = document.getElementById("optionFromStart").value;
        var _token = "{{csrf_token()}}"

        // console.log(id);
        // console.log(percent);
        // console.log(_token);

        document.getElementById('button-modal-from-10').style.display="none";
        document.getElementById('button-modal-from-20').style.display="none";
        document.getElementById('button-modal-from-30').style.display="none";
        document.getElementById('button-modal-from-40').style.display="none";
        document.getElementById('button-modal-from-50').style.display="none";
        document.getElementById('button-modal-from-60').style.display="none";
        document.getElementById('button-modal-from-70').style.display="none";
        document.getElementById('button-modal-from-80').style.display="none";
        document.getElementById('button-modal-from-90').style.display="none";
        document.getElementById('button-modal').style.display="none";
        document.getElementById('spinner').style.display="inline";

        $.ajax({
          url: '/fromstart',
          type: 'post',
          data:{'id':id,'percent':percent,'_token':_token},

          success: function(){ 
            document.getElementById('button-modal').style.display="inline";
            document.getElementById('spinner').style.display="none";
            location.reload();
            },
            error:function(stato){
            //   console.log()
              console.log(stato);
              document.getElementById('button-modal').style.display="inline";
              document.getElementById('spinner').style.display="none";
              alert("encountered an unrecognized error!");
             
            }
      }) //fine chiamata ajax
    }

/**
@brief start the retrive of the pubblications from last stop 

as long as the recovery is in progress the modal will show a "spinner" and rimmarerà until the withdrawal ends

@param percent
@return none
*/
  function executeFromLast(percent)
  {
        // var id = document.getElementById("inputID").value;
        var id = localStorage.getItem('queryID');
        // var percent = document.getElementById("optionFromLast").value;
        var _token = "{{csrf_token()}}"

        // console.log(id);
        // console.log(percent);
        // console.log(_token);

        document.getElementById('button-modal-from-10').style.display="none";
        document.getElementById('button-modal-from-20').style.display="none";
        document.getElementById('button-modal-from-30').style.display="none";
        document.getElementById('button-modal-from-40').style.display="none";
        document.getElementById('button-modal-from-50').style.display="none";
        document.getElementById('button-modal-from-60').style.display="none";
        document.getElementById('button-modal-from-70').style.display="none";
        document.getElementById('button-modal-from-80').style.display="none";
        document.getElementById('button-modal-from-90').style.display="none";
        document.getElementById('button-modal').style.display="none";
        document.getElementById('spinner').style.display="inline";

        $.ajax({
          url: '/fromstop',
          type: 'post',
          data:{'id':id,'percent':percent,'_token':_token},

          success: function(){ 
            document.getElementById('button-modal').style.display="inline";
            document.getElementById('spinner').style.display="none";
            location.reload();
            },
            error:function(stato){
            //   console.log()
              console.log(stato);
              document.getElementById('button-modal').style.display="inline";
              document.getElementById('spinner').style.display="none";
              alert("encountered an unrecognized error!");
             
            }
      }) //fine chiamata ajax
 }

// function radioClick(item)
// {
//   // console.log(item['name']);

//   document.getElementById("HCurrentProjectp").innerHTML = "Current Project : "+item['name'];
//   document.getElementById("HCurrentOpep").innerHTML = "Current Operation : Idle";
//   document.getElementById("HDescp").innerHTML = "Short Description : "+item['description_short'];

//   var tag = document.getElementById("linkTrain");
//   tag.className = 'nav-link active font';
//   // var id=item['id'];
//   var url = '{{URL::action("QueryController@train",":item")}}';
//   url=url.replace(':item',item['id']);


//   // console.log(url);
//   tag.setAttribute('href',url);
// }

/**
@brief show the modal for the execute

this will show modals with and based on the percentage of recovered saved on the database the modal will show different inputs

@param id
@return none
*/
function executeChoice(id){

  localStorage.setItem('queryID',id);

  document.getElementById('button-modal-from-10').style.display="none";
  document.getElementById('button-modal-from-20').style.display="none";
  document.getElementById('button-modal-from-30').style.display="none";
  document.getElementById('button-modal-from-40').style.display="none";
  document.getElementById('button-modal-from-50').style.display="none";
  document.getElementById('button-modal-from-60').style.display="none";
  document.getElementById('button-modal-from-70').style.display="none";
  document.getElementById('button-modal-from-80').style.display="none";
  document.getElementById('button-modal-from-90').style.display="none";
  document.getElementById('button-modal-from-100').style.display="none";

  // console.log("yes");
  var id = localStorage.getItem('queryID');
  var _token = "{{csrf_token()}}";

  $.ajax({
          url: '/checkretstart',
          type: 'post',
          data:{'id':id,'_token':_token},

          success: function(response){ 

              console.log(response);

              tok = response.split('|');
              
              var start = parseInt(tok[0]);

              var preexc = parseInt(tok[2]);

              var percent = parseFloat(tok[1]);

              if(start !=0)
              {
                
                if(!(start > preexc))
                {
                 
                    if(percent >= 10 && percent < 20)
                    {

                      document.getElementById('button-modal-from-10').style.display="inline";
                      document.getElementById('button-modal-from-20').style.display="none";
                      document.getElementById('button-modal-from-30').style.display="none";
                      document.getElementById('button-modal-from-40').style.display="none";
                      document.getElementById('button-modal-from-50').style.display="none";
                      document.getElementById('button-modal-from-60').style.display="none";
                      document.getElementById('button-modal-from-70').style.display="none";
                      document.getElementById('button-modal-from-80').style.display="none";
                      document.getElementById('button-modal-from-90').style.display="none";

                    }
                    else if (percent >= 20 && percent < 30)
                    {

                      document.getElementById('button-modal-from-10').style.display="none";
                      document.getElementById('button-modal-from-20').style.display="inline";
                      document.getElementById('button-modal-from-30').style.display="none";
                      document.getElementById('button-modal-from-40').style.display="none";
                      document.getElementById('button-modal-from-50').style.display="none";
                      document.getElementById('button-modal-from-60').style.display="none";
                      document.getElementById('button-modal-from-70').style.display="none";
                      document.getElementById('button-modal-from-80').style.display="none";
                      document.getElementById('button-modal-from-90').style.display="none";

                    }
                    else if (percent >= 30 && percent < 40)
                    {

                      document.getElementById('button-modal-from-10').style.display="none";
                      document.getElementById('button-modal-from-20').style.display="none";
                      document.getElementById('button-modal-from-30').style.display="inline";
                      document.getElementById('button-modal-from-40').style.display="none";
                      document.getElementById('button-modal-from-50').style.display="none";
                      document.getElementById('button-modal-from-60').style.display="none";
                      document.getElementById('button-modal-from-70').style.display="none";
                      document.getElementById('button-modal-from-80').style.display="none";
                      document.getElementById('button-modal-from-90').style.display="none";

                    }
                    else if (percent >= 40 && percent < 50)
                    {

                      document.getElementById('button-modal-from-10').style.display="none";
                      document.getElementById('button-modal-from-20').style.display="none";
                      document.getElementById('button-modal-from-30').style.display="none";
                      document.getElementById('button-modal-from-40').style.display="inline";
                      document.getElementById('button-modal-from-50').style.display="none";
                      document.getElementById('button-modal-from-60').style.display="none";
                      document.getElementById('button-modal-from-70').style.display="none";
                      document.getElementById('button-modal-from-80').style.display="none";
                      document.getElementById('button-modal-from-90').style.display="none";

                    }
                    else if (percent >= 50 && percent < 60)
                    {

                      document.getElementById('button-modal-from-10').style.display="none";
                      document.getElementById('button-modal-from-20').style.display="none";
                      document.getElementById('button-modal-from-30').style.display="none";
                      document.getElementById('button-modal-from-40').style.display="none";
                      document.getElementById('button-modal-from-50').style.display="inline";
                      document.getElementById('button-modal-from-60').style.display="none";
                      document.getElementById('button-modal-from-70').style.display="none";
                      document.getElementById('button-modal-from-80').style.display="none";
                      document.getElementById('button-modal-from-90').style.display="none";

                    }
                    else if (percent >= 60 && percent < 70)
                    {

                      document.getElementById('button-modal-from-10').style.display="none";
                      document.getElementById('button-modal-from-20').style.display="none";
                      document.getElementById('button-modal-from-30').style.display="none";
                      document.getElementById('button-modal-from-40').style.display="none";
                      document.getElementById('button-modal-from-50').style.display="none";
                      document.getElementById('button-modal-from-60').style.display="inline";
                      document.getElementById('button-modal-from-70').style.display="none";
                      document.getElementById('button-modal-from-80').style.display="none";
                      document.getElementById('button-modal-from-90').style.display="none";

                    }
                    else if (percent >= 70 && percent < 80)
                    {

                      document.getElementById('button-modal-from-10').style.display="none";
                      document.getElementById('button-modal-from-20').style.display="none";
                      document.getElementById('button-modal-from-30').style.display="none";
                      document.getElementById('button-modal-from-40').style.display="none";
                      document.getElementById('button-modal-from-50').style.display="none";
                      document.getElementById('button-modal-from-60').style.display="none";
                      document.getElementById('button-modal-from-70').style.display="inline";
                      document.getElementById('button-modal-from-80').style.display="none";
                      document.getElementById('button-modal-from-90').style.display="none";

                    }
                    else if (percent >= 80 && percent < 90)
                    {

                      document.getElementById('button-modal-from-10').style.display="none";
                      document.getElementById('button-modal-from-20').style.display="none";
                      document.getElementById('button-modal-from-30').style.display="none";
                      document.getElementById('button-modal-from-40').style.display="none";
                      document.getElementById('button-modal-from-50').style.display="none";
                      document.getElementById('button-modal-from-60').style.display="none";
                      document.getElementById('button-modal-from-70').style.display="none";
                      document.getElementById('button-modal-from-80').style.display="inline";
                      document.getElementById('button-modal-from-90').style.display="none";

                    }
                    else if (percent >= 90 && percent < 100)
                    {

                      document.getElementById('button-modal-from-10').style.display="none";
                      document.getElementById('button-modal-from-20').style.display="none";
                      document.getElementById('button-modal-from-30').style.display="none";
                      document.getElementById('button-modal-from-40').style.display="none";
                      document.getElementById('button-modal-from-50').style.display="none";
                      document.getElementById('button-modal-from-60').style.display="none";
                      document.getElementById('button-modal-from-70').style.display="none";
                      document.getElementById('button-modal-from-80').style.display="none";
                      document.getElementById('button-modal-from-90').style.display="inline";

                    }
              
                }//fine if pre > start
                else
                {
                  
                  document.getElementById('button-modal-from-100').style.display="inline";
                  document.getElementById('button-modal-from-10').style.display="none";
                  document.getElementById('button-modal-from-20').style.display="none";
                  document.getElementById('button-modal-from-30').style.display="none";
                  document.getElementById('button-modal-from-40').style.display="none";
                  document.getElementById('button-modal-from-50').style.display="none";
                  document.getElementById('button-modal-from-60').style.display="none";
                  document.getElementById('button-modal-from-70').style.display="none";
                  document.getElementById('button-modal-from-80').style.display="none";
                  document.getElementById('button-modal-from-90').style.display="none";
                }

              }//fine prim if 

            },
            error:function(stato){
            //   console.log()
              console.log(stato);
              document.getElementById('button-modal').style.display="inline";
              document.getElementById('spinner').style.display="none";
              alert("encountered an unrecognized error!");
             
            }
      }) //fine chiamata ajax

}//fine exuction choice


  </script>

  
@endsection