@extends('layouts.app')

@section('content')
{{-- 
<style>
   .download-grafico {
        position: absolute;
        top: 20px;
        right: 15px;
    }

    .download-grafico i {
        -moz-transition: all 0.5s;
        -o-transition: all 0.5s;
        -webkit-transition: all 0.5s;
        transition: all 0.5s;
        color: #999;
    }

    .download-grafico i:hover {
        color: #000;
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


    <div class="col-md-12 col-sm-12 col-xs-12">
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
        <button type="submit" class="btn btn-primary btn-sm">Send</button>
    </div>

    </form>


    {{-- <div>

        @foreach ($result as $item)
                 <p>"{{$item->name}}" with {{$item->counter}} </p>
        @endforeach
    
    </div> --}}


    <div class="col-md-12 col-sm-12 col-xs-12">

    <div class="my-2">
      <label> Bar chart First Author</label>
      <a id="download" 
      download="" 
      href=""
      class="btn btn-primary float-right ml-2 mb-2 "
      title="Download Bar chart First Author">

      <!-- Download Icon -->
      <i class="fa fa-download"></i>
      </a>

  </div>
       
        <canvas id="chart" style="position: relative; height:75vh; width:80vw"></canvas>


    
    </div>


    <div class="col-md-12 col-sm-12 col-xs-12">

      <div class="my-2">
        <label> Bar chart Last Author</label>
        <a id="downloadLast" 
        download="" 
        href=""
        class="btn btn-primary float-right ml-2 mb-2 "
        title="Download Bar chart Last Author">
  
        <!-- Download Icon -->
        <i class="fa fa-download"></i>
        </a>
  
    </div>
         
          <canvas id="chartLast" style="position: relative; height:75vh; width:80vw"></canvas>
  
  
      
      </div>

   <div class="col-md-12 col-sm-12 col-xs-12">

    <div class="my-2">
      <label> Line chart First Author</label>
      <a id="downloadLine" 
      download="" 
      href=""
      class="btn btn-primary float-right ml-2 mb-2 "
      title="Download Line chart First Author">

      <!-- Download Icon -->
      <i class="fa fa-download"></i>
      </a>

  </div>
       
      <canvas id="linechart" style="position: relative; height:150vh; width:80vw"></canvas>
  
  </div> 

  <div class="col-md-12 col-sm-12 col-xs-12">

    <div class="my-2">
      <label> Line chart Last Author</label>
      <a id="downloadLineLast" 
      download="" 
      href=""
      class="btn btn-primary float-right ml-2 mb-2 "
      title="Download Line chart Last Author">

      <!-- Download Icon -->
      <i class="fa fa-download"></i>
      </a>

  </div>
       
      <canvas id="linechartLast" style="position: relative; height:150vh; width:80vw"></canvas>
  
  </div> 

</div> 

















<script type="text/javascript">

var result_fa_fn = @json($result_fa_fn);
var result_fa_ln = @json($result_fa_ln);
var result_fa_name = @json($result_fa_name);
var result_fa_count = @json($result_fa_count);

var result_la_fn = @json($result_la_fn);
var result_la_ln = @json($result_la_ln);
var result_la_name = @json($result_la_name);
var result_la_count = @json($result_la_count);




var lineChart =@json($lineChart);

var lineChartLastAuthor =@json($lineChartLastAuthor);

var len = lineChart.length;

var gran = 0;
// console.log(result_name_j);

data = [];
colors=[];
labels = [];
datasets = [];
var label="Total Result";


// var string;
// var count;

for (var i = 0; i < result_fa_name.length; i++)
{

  data.push(result_fa_count[i].counter);


  labels.push(result_fa_fn[i].first_name+" "+result_fa_ln[i].last_name);


  var color = random_rgba();
  colors.push(color);


}//fine for

var datasets = [{
                          label: label,
                          backgroundColor: colors,
                          borderColor: colors,
                          data: data
                      }]


//creazione grafico a barre per last author

dataLast = [];
colorsLast =[];
labelsLast = [];
datasetsLast = [];
var label="Total Result";


// var string;
// var count;

for (var i = 0; i < result_la_name.length; i++)
{

  dataLast.push(result_la_count[i].counter);


  labelsLast.push(result_la_fn[i].first_name+" "+result_la_ln[i].last_name);


  var color = random_rgba();
  colorsLast.push(color);


}//fine for

var datasetsLast = [{
                          label: label,
                          backgroundColor: colorsLast,
                          borderColor: colorsLast,
                          data: dataLast
                      }]



//creazione linechart

var dataLineChart = [];//vettore che contiene i dati come valore numerico
var datasetsvals = [];//creo il vettore che contiene l'oggetto
var labelsValsLine = [];//vettore che contiene il valore anni
var lenI = lineChart[0].length;//lunghezza vettore interno
var labelLineChartString;//contiene il nome della label (Es classe A,B,C)

// console.log(len);
// console.log(lineChart);

var sconosciuto = false;

for(var i = 0; i < len; i++)
{
   
    

    for(var key in lineChart[i])
    {
        if(lineChart[i][key].pubblication_year != null)
        {
            if(!labelsValsLine.includes(lineChart[i][key].pubblication_year))
            {
              labelsValsLine.push(lineChart[i][key].pubblication_year);
            }
        }  
        else
        {       
            sconosciuto = true;
        }


    }

}//fine for

if(sconosciuto == true){
      labelsValsLine.push('unknown year');
}

var check = false;

for(var i = 0; i < len; i++)
{
  dataLineChart = [];

    for(var key in lineChart[i])
    {    
       for(var j=0;j < labelsValsLine.length; j++)
       {
          if(lineChart[i][key].pubblication_year == labelsValsLine[j] || lineChart[i][key].pubblication_year == null)
          {
               dataLineChart.push(lineChart[i][key].counter);
          }
          else
          {
              dataLineChart.push(0);
          }


       }
      
    }//fine for interno

  

  var color = random_rgba();

  if(lineChart[i].length != 0)
  {

    datasetsvals.push(
    {
        label: lineChart[i][0].first_name+" "+lineChart[i][0].last_name+" "+lineChart[i][0].name,
        data: dataLineChart,
        borderWidth: 2,
        lineTension:0,
        fill: false,
        borderColor: color,
        pointBorderWidth: 2,
        pointRadius: 3,
        pointBackgroundColor: color,
        pointBorderColor: color,
    }
    );

  }//fine if

}//fine for esterno

//creazione linechartLast

var lenLast = lineChartLastAuthor.length;
var dataLineChartLast = [];//vettore che contiene i dati come valore numerico
var datasetsvalsLast = [];//creo il vettore che contiene l'oggetto
var labelsValsLineLast = [];//vettore che contiene il valore anni
var lenI = lineChartLastAuthor[0].length;//lunghezza vettore interno
var labelLineChartStringLast;//contiene il nome della label (Es classe A,B,C)



var sconosciutoLast = false;

for(var i = 0; i < lenLast; i++)
{
    for(var key in lineChartLastAuthor[i])
    {
        if(lineChartLastAuthor[i][key].pubblication_year != null)
        {
            if(!labelsValsLineLast.includes(lineChartLastAuthor[i][key].pubblication_year))
            {
              labelsValsLineLast.push(lineChartLastAuthor[i][key].pubblication_year);
            }
        }  
        else
        {       
          sconosciutoLast = true;
        }
    }

}//fine for

if(sconosciutoLast == true){
      labelsValsLineLast.push('unknown year');
}

var checkLast = false;

for(var i = 0; i < lenLast; i++)
{
  dataLineChartLast = [];

    for(var key in lineChartLastAuthor[i])
    {    
       for(var j=0;j < labelsValsLineLast.length; j++)
       {
          if(lineChartLastAuthor[i][key].pubblication_year == labelsValsLineLast[j] || lineChartLastAuthor[i][key].pubblication_year == null)
          {
               dataLineChartLast.push(lineChartLastAuthor[i][key].counter);
          }
          else
          {
              dataLineChartLast.push(0);
          }


       }
      
    }//fine for interno

  

  var color = random_rgba();

  if(lineChartLastAuthor[i].length != 0)
  {

    datasetsvalsLast.push(
    {
        label: lineChartLastAuthor[i][0].first_name+" "+lineChartLastAuthor[i][0].last_name+" "+lineChartLastAuthor[i][0].name,
        data: dataLineChartLast,
        borderWidth: 2,
        lineTension:0,
        fill: false,
        borderColor: color,
        pointBorderWidth: 2,
        pointRadius: 3,
        pointBackgroundColor: color,
        pointBorderColor: color,
    }
    );

  }//fine if

}//fine for esterno

console.log(labelsValsLineLast);
// console.log(datasetsvals);


var ctx = document.getElementById("chart").getContext("2d");
// ctx.style.backgroundColor = 'rgba(255,0,0,255)';

var mybarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: datasets
  },

  plugins: {
      beforeDraw: function (chart, easing) {
        var ctx = chart.chart.ctx;
        ctx.save();
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
      }
    },

  options: {
    responsive:true,
    legend: {
      display: false,
      position: 'top',
      labels: {
        fontColor: "#000080",
      }
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    animation: {
      onComplete: function() {
        var a =  document.getElementById("download");
        a.href = mybarChart.toBase64Image();
        a.download="my_file_name.jpeg";

        // console.log(a.href);
      }
    }
  }
});

// creazoine grafico a barre
var ctxLast = document.getElementById("chartLast").getContext("2d");
// ctx.style.backgroundColor = 'rgba(255,0,0,255)';

var ChartLast = new Chart(ctxLast, {
  type: 'bar',
  data: {
    labels: labelsLast,
    datasets: datasetsLast
  },

  plugins: {
      beforeDraw: function (chart, easing) {
        var ctxLast = chart.chart.ctx;
        ctxLast.save();
        ctxLast.fillStyle = "#ffffff";
        ctxLast.fillRect(0, 0, chart.width, chart.height);
        ctxLast.restore();
      }
    },

  options: {
    responsive:true,
    legend: {
      display: false,
      position: 'top',
      labels: {
        fontColor: "#000080",
      }
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    animation: {
      onComplete: function() {

        var a =  document.getElementById("downloadLast");
        a.href = ChartLast.toBase64Image();
        a.download="my_file_name.jpeg";

      }
    }
  }
});


var line = document.getElementById("linechart").getContext("2d");


var mylineChart = new Chart(line, {
  type: 'line',
  data: {
                labels: labelsValsLine,
                datasets: datasetsvals,
            },
            plugins: {
      beforeDraw: function (chart, easing) {
        var ctx = chart.chart.ctx;
        ctx.save();
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
      }
    },
  options: {
    legend: {
      display: true,
      position: 'top',
      labels: {
        fontColor: "#000080",
      }
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    animation: {
      onComplete: function() {
        var a =  document.getElementById("downloadLine");
        a.href = mylineChart.toBase64Image();
        a.download="my_file_name.jpeg";

        // console.log(a.href);
      }
    }
  }
});

//line chart last

var lineLast = document.getElementById("linechartLast").getContext("2d");


var lineChartLast = new Chart(lineLast, {
  type: 'line',
  data: {
                labels: labelsValsLineLast,
                datasets: datasetsvalsLast,
            },
            plugins: {
      beforeDraw: function (chart, easing) {
        var ctx = chart.chart.ctx;
        ctx.save();
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
      }
    },
  options: {
    legend: {
      display: true,
      position: 'top',
      labels: {
        fontColor: "#000080",
      }
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    animation: {
      onComplete: function() {
        var a =  document.getElementById("downloadLineLast");
        a.href = lineChartLast.toBase64Image();
        a.download="my_file_name.jpeg";

        // console.log(a.href);
      }
    }
  }
});



 function stati()
 {
    document.getElementById('statistics').style.display = "inline";
    document.getElementById('author').style.display = "none";
    document.getElementById('submit').style.display = "inline";

 } 

 function analis()
 {
    document.getElementById('statistics').style.display = "none";
    document.getElementById('author').style.display = "inline";
    document.getElementById('submit').style.display = "inline";
    
 }    

 function change(selector)
 {
    var val = selector.value;

    document.getElementById('type-of').value = val;

    // alert(document.getElementById('type-of').value);
 }

 function random_rgba() {
    var o = Math.round, r = Math.random, s = 255;

    return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ')';

}

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





</script>

  
@endsection