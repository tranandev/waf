@extends('layouts.app')
@section('content')

<style>
  .panel-heading {
        padding: 8px 14px;
  }
</style>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="js/canvas.js"></script>

<div style="overflow: auto; margin-left: 5px; margin-right: 20px; border: 1px solid #ccc; border-radius: 5px; width: 100%;">
  <header class="panel-heading">
    <b><i class="fa fa-globe"></i> Info</b>
  </header>
  <div>
    <div class="col-md-12 col-sm-12" style="overflow-x: hidden; margin-bottom: 20px;">
      <div class="col-md-4 col-sm-4" style="margin-top: 20px; float: left;">
        Name:
      </div>
      <div class="col-md-8 col-sm-8" style="margin-top: 20px; float: left;">
        WAF - LAQ
      </div>
      <div class="col-md-4 col-sm-4" style="margin-top: 20px; float: left;">
        Version:
      </div>
      <div class="col-md-8 col-sm-8" style="margin-top: 20px; float: left;">
        1.0
      </div>
      <div class="col-md-4 col-sm-4" style="margin-top: 20px; float: left;">
        CPU:
      </div>
      <div class="col-md-8 col-sm-8" style="margin-top: 20px; float: left;">
        {{$cpu}}
      </div>
      <div class="col-md-4 col-sm-4" style="margin-top: 20px; float: left;">
        Memory:
      </div>
      <div class="col-md-8 col-sm-8" style="margin-top: 20px; float: left;">
        {{$mem}}
      </div>
      <div class="col-md-4 col-sm-4" style="margin-top: 20px; float: left;">
        HDD:
      </div>
      <div class="col-md-8 col-sm-8" style="margin-top: 20px; float: left;">
        {{$hdd}}
      </div>
    </div>
  </div>
</div>

<div class="col-md-12 col-sm-12" style="margin-top: 15px; margin-left: 5px; margin-right: 20px; border: 1px solid #ccc; border-radius: 5px;">
<div id="chartContainer" style="height: 400px; width: 100%;">
</div>
</div>
<script>

var yValue1, yValue2, yValue3;
var all_data;
$.ajax({
    url: '/get-old-resource',
    type: 'GET',
    success: function (data) {
    all_data = jQuery.parseJSON(data);
    console.log(all_data);
    }
});

var source = new EventSource("{{route('get-resource')}}");
        source.onmessage = function(e) {
            resource = JSON.parse(e.data);
            yValue1 = resource.cpu;
            yValue2 = resource.ram;
            yValue3 = resource.disk;
        };

window.onload = function () {

var dataPoints1 = [];
var dataPoints2 = [];
var dataPoints3 = [];

var options = {
  title: {
    text: "Resource"
  },
  axisX: {
    title: ""
  },
  axisY: {
    suffix: "%",
    includeZero: false
  },
  toolTip: {
    shared: true
  },
  legend: {
    cursor: "pointer",
    verticalAlign: "top",
    fontSize: 16,
    fontColor: "dimGrey",
    itemclick: toggleDataSeries
  },
  data: [{
    type: "spline",
    xValueType: "dateTime",
    yValueFormatString: "###,##%",
    xValueFormatString: "hh:mm:ss TT",
    showInLegend: true,
    name: "CPU",
    dataPoints: dataPoints1
  },
  {
    type: "spline",
    xValueType: "dateTime",
    yValueFormatString: "###,##%",
    showInLegend: true,
    name: "RAM",
    dataPoints: dataPoints2
  },
  {
    type: "spline",
    xValueType: "dateTime",
    yValueFormatString: "###,##%",
    showInLegend: true,
    name: "DISK",
    dataPoints: dataPoints3
  }]
};

var chart = new CanvasJS.Chart("chartContainer", options);

function toggleDataSeries(e) {
  if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
    e.dataSeries.visible = false;
  }
  else {
    e.dataSeries.visible = true;
  }
  e.chart.render();
}

var updateInterval = 1000;

var dataLength = 100;
var time = new Date;

function updateChart(count) {
  count = count || 1;

  for (var i = count-2 ; i >= 0 ; i--) {
    time.setTime(time.getTime() + updateInterval);
    yValue1 = all_data[i].cpu;
    yValue2 = all_data[i].ram;
    yValue3 = all_data[i].disk;
    // pushing the new values
    dataPoints1.push({
      x: time.getTime(),
      y: yValue1
    });
    dataPoints2.push({
      x: time.getTime(),
      y: yValue2
    });
    dataPoints3.push({
      x: time.getTime(),
      y: yValue3
    });
  }
  if (dataPoints1.length > dataLength) {
    dataPoints1.shift();
  }
  if (dataPoints2.length > dataLength) {
    dataPoints2.shift();
  }
  if (dataPoints3.length > dataLength) {
    dataPoints3.shift();
  }
  // updating legend text with  updated with y Value 
  options.data[0].legendText = "CPU : " + yValue1 + "%";
  options.data[1].legendText = "RAM : " + yValue2 + "%";
  options.data[2].legendText = "DISK : " + yValue3 + "%";
  chart.render();
}

function updateChart1(count) {
  count = count || 1;
  for (var i = 0; i < count; i++) {
    time.setTime(time.getTime() + updateInterval);

    // pushing the new values
    dataPoints1.push({
      x: time.getTime(),
      y: yValue1
    });
    dataPoints2.push({
      x: time.getTime(),
      y: yValue2
    });
    dataPoints3.push({
      x: time.getTime(),
      y: yValue3
    });
  }
  if (dataPoints1.length > dataLength) {
    dataPoints1.shift();
  }
  if (dataPoints2.length > dataLength) {
    dataPoints2.shift();
  }
  if (dataPoints3.length > dataLength) {
    dataPoints3.shift();
  }
  // updating legend text with  updated with y Value 
  options.data[0].legendText = "CPU : " + yValue1 + "%";
  options.data[1].legendText = "RAM : " + yValue2 + "%";
  options.data[2].legendText = "DISK : " + yValue3 + "%";
  chart.render();
}

// generates first set of dataPoints 
updateChart(100);
setInterval(function () { updateChart1() }, updateInterval);

}
</script>
@endsection
