

<div class="container" class="col-md-12 col-sm-12 col-xs-12">

    {{csrf_field()}}
    {{-- <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"> --}}

    @foreach ($q as $item)
        
    <div>
        {{-- <p>Total results : {{$item->pre_exc}}</p> --}}
        <input type="text" id="inputID" value="{{$item->id}}" hidden>
        <input type="text" id="_token" value="{{csrf_token()}}" hidden>
    </div>

    <div>
         <label>Execute at to:</label>
         <select id="optionFromStart">
             <option value="10%">10%</option><option value="20%">20%</option><option value="30%">30%</option><option value="40%">40%</option><option value="50%">50%</option><option value="60%">60%</option><option value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
         </select>
         <a href  onclick="executeStart()" class="btn btn-outline-secondary btn-sm float-md-right">Execute</a>
       
    </div>

    <div class="my-2">
        {{-- <p>Or</p> --}}
    </div>
    @if($item->ret_start != 0)

        <div >
            <label>Execute at to from:</label>
            <select id="optionFromStop">
                <option value="10%">10%</option><option value="20%">20%</option><option value="30%">30%</option><option value="40%">40%</option><option value="50%">50%</option><option value="60%">60%</option><option value="70%">70%</option><option value="80%">80%</option><option value="90%">90%</option><option value="100%">100%</option>
            </select>
            <a href="" class="btn btn-outline-secondary btn-sm float-md-right">Execute</a>
        </div>
    {{-- @else 

        <div>
            <label>You never Execute the Query!</label>
            <a href="" class="btn btn-outline-secondary disabled">Execute from last stop</a>
        </div> --}}
    @endif 

    @endforeach
</div>

<script
src="https://code.jquery.com/jquery-3.5.1.min.js"
integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
crossorigin="anonymous"></script>

<script type="text/javascript">

    function executeStart()
    {
        var id = document.getElementById("inputID").value;
        var percent = document.getElementById("optionFromStart").value;
        var _token = document.getElementById("_token").value;


        $.ajax({
          url: '/fromstart',
          type: 'post',
          data:{'id':id,'percent':percent,'_token':_token},

          success: function(){

                console.log("si");     
        
            },
            error:function(stato){
            //   console.log()
              console.log(stato);
            }
      }) //fine chiamata ajax






    }
  
    </script>