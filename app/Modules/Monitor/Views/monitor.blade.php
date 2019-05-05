@extends('layouts.app')
@section('content')


<link rel="stylesheet" href="{{ asset('css/chartist.min.css') }}">
<script src="{{ asset('js/chartist.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/canvasjs.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/loader.js') }}"></script>
<!-- <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyAVst_8nzRED6w_sJbw7hPiWCes1IRzXgE" async="" defer="defer"></script> -->

<div class="modal1"><!-- Place at bottom of page --></div>
<div class="row">
    <div class="col-sm-6">
        <label for="top10-ip-period" style="float: left; margin-left: 20px; margin-right: 10px;">Period</label>
            <select name="" id="top10-ip-period" style="width: auto;" onchange="loadIP()">
                <option value="3600">60 minutes</option>
                <option value="86400">24 hours</option>
                <option value="259200">3 days</option>
                <option value="2592000">30 days</option>
                <option value="31556926">1 year</option>
            </select>
    </div>
    <div class="col-sm-6">
        <label for="top10-at-period" style="float: left; margin-left: 20px; margin-right: 10px;">Period</label>
            <select name="" id="top10-at-period" style="width: auto;" onchange="loadAT()">
                <option value="3600">60 minutes</option>
                <option value="86400">24 hours</option>
                <option value="259200">3 days</option>
                <option value="2592000">30 days</option>
                <option value="31556926">1 year</option>
            </select>
    </div>
</div>
<div class="row" style="margin-bottom: 40px">
    <div class="col-sm-6">
            <div id="chartContainerIP" style="height: 300px; width: 100%; margin-right: 20px;"></div>
    </div>
    <div class="col-sm-6">
            <div id="chartContainerAttack" style="height: 300px; width: 100%;"></div>
    </div>
</div>
<table id="MonitorTable" class="table table-striped table-bordered thead-dark" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>IP</th>
                <th>Time</th>
                <th>Country</th>
                <th>Group Website</th>
                <th>Website</th>
                <th>GroupRule</th>
            </tr>
        </thead>
    </table>
<script>

var isEdit = false;
var table;
var listip = [];
var listat = [];
$(document).ready(function() {
        table = $('#MonitorTable').DataTable( {
        lengthChange: false,
        ajax: "{{route('get-monitor-dataTable')}}",
        columns: [
            { data: "id" },
            { data: "ip" },
            { data: null, render: function ( data, type, row ) {
                var date = new Date(row.time*1000);
                year=date.getFullYear();
                month=(date.getMonth()+1).toString().padStart(2, "0");
                day=date.getDate().toString().padStart(2, "0");
                hours=date.getHours().toString().padStart(2, "0");
                minutes=date.getMinutes().toString().padStart(2, "0");
                sec=date.getSeconds().toString().padStart(2, "0");
                time=day + "/" + month + "/" + year + " " + hours + ":" + minutes + ":" + sec;
                return time;
            } },
            { data: "country" },
            { data: null, render: function ( data, type, row ) {
                return row.groupwebsite.name;
            } },
            { data: null, render: function ( data, type, row ) {
                return row.website.name;
            } },
            { data: null, render: function ( data, type, row ) {
                return row.grouprule.name;
            } }
        ],
        select: true
    } );
});



function reloadMonitorTable() {
    table.ajax.reload(null, false );
}


var optionip = {
    exportEnabled: true,
    animationEnabled: true,
    title:{
        text: "Top 10 IP"
    },
    legend:{
        horizontalAlign: "right",
        verticalAlign: "center"
    },
    data: [{
        type: "pie",
        showInLegend: true,
        toolTipContent: "<b>{name}</b>: ${y} (#percent%)",
        indexLabel: "{name}",
        legendText: "{name} (#percent%)",
        indexLabelPlacement: "inside",
        dataPoints: listip
    }]
};

var optionat = {
    exportEnabled: true,
    animationEnabled: true,
    title:{
        text: "Top 10 Attack"
    },
    legend:{
        horizontalAlign: "right",
        verticalAlign: "center"
    },
    data: [{
        type: "pie",
        showInLegend: true,
        toolTipContent: "<b>{name}</b>: ${y} (#percent%)",
        indexLabel: "{name}",
        legendText: "{name} (#percent%)",
        indexLabelPlacement: "inside",
        dataPoints: [
            { y: 6566.4, name: "Housing" },
            { y: 2599.2, name: "Food" },
            { y: 1231.2, name: "Fun" },
            { y: 1368, name: "Clothes" },
            { y: 684, name: "Others"},
            { y: 1231.2, name: "Utilities" }
        ]
    }]
};

// $("#chartContainerAttack").CanvasJSChart(optionat);
// $("#chartContainerIP").CanvasJSChart(optionip);


function loadIP() {
    $.ajax({
        method: "GET",
        url: "{{route('get-ip')}}?period="+$("#top10-ip-period").val(),
        async:false,
        success : function(a){
            listip = JSON.parse(a);
            console.log(listip);
        var optionip = {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "Top 10 IP"
                },
                legend:{
                    horizontalAlign: "right",
                    verticalAlign: "center"
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "<b>{ip}</b>: ${y} (#percent%)",
                    legendText: "{ip} (#percent%)",
                    dataPoints: listip
                }]
            };
        $("#chartContainerIP").CanvasJSChart(optionip);
        },
    });
}

function loadAT() {
    $.ajax({
        method: "GET",
        url: "{{route('get-attack')}}?period="+$("#top10-at-period").val(),
        async:false,
        success : function(a){
            listat = JSON.parse(a);
            console.log(listat);
            var optionat = {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "Top 10 Attack"
                },
                legend:{
                    horizontalAlign: "right",
                    verticalAlign: "center"
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "<b>{attack}</b>: ${y} (#percent%)",
                    legendText: "{attack} (#percent%)",
                    dataPoints: listat,
                }]
            };
            $("#chartContainerAttack").CanvasJSChart(optionat);
        },
    });
}

$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});
</script>

@endsection