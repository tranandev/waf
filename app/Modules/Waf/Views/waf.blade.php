@extends('layouts.app')
@section('content')
<div class="modal1"><!-- Place at bottom of page --></div>
<table id="GroupWebsiteTable" class="table table-striped table-bordered thead-dark" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th style="width: 60px;">On/Off</th>
                <th style="width: 35px">Rule</th
            </tr>
        </thead>
    </table>
<script>
var isEdit = false;
var table;
var wafDetail="";
$(document).ready(function() {
        table = $('#GroupWebsiteTable').DataTable( {
        lengthChange: false,
        ajax: "{{route('get-dataTable')}}",
        columns: [
            { data: "name" },
            { data: "description" },
            { data: null, render: function ( data, type, row ) {
                if (data.status == 0) return '<input type="checkbox" data-toggle="toggle" id = "toggle'+ data.id +'" class="toggle-button" data-size="small" data-onstyle="success" onchange="changeGroupWebsiteStatus(\''+ data.id +'\')">';
                else return '<input type="checkbox" checked data-toggle="toggle" id = "toggle'+ data.id +'" class="toggle-button" data-size="small" data-onstyle="success" onchange="changeGroupWebsiteStatus(\''+ data.id +'\')">';
            } },
            { data: null, render: function ( data, type, row ) {
                return '<button class="btn btn-success btn-sm" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i style="margin-left:6px; margin-right:6px;" class="fa fa-lg fa-toggle-down" title="Edit" aria-hidden="true" id="editRule_'+ data.id +'" onclick="editRule(\''+ data.id +'\')"></i></button>';
            } }
        ],
        select: true,
        "initComplete": function(settings, json) {
            $('.toggle-button').bootstrapToggle();
        }
    }); 
});

// function reloadGroupWebsiteTable() {
//     GroupWebsiteTable.ajax.reload(null, false );
// }

function changeGroupWebsiteStatus(id) {
    var data = new FormData();
    data.append("id",id);
    $.ajax({
        method: "POST",
        url: "{{route('change-group-website-status')}}",
        data: data,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData: false,
        contentType: false,
        success : function(a){
            // reloadGroupWebsiteTable();
            something_change = 1;
        },
    });
}

function getGroupRule(id) {
    $.ajax({
        method: "GET",
        url: "{{route('get-group-rule')}}?id="+id,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData: false,
        async: false,
        success : function(a){
            groupRule = a;
            wafDetail = '<tr id="waf-detail"><td colspan="5">';
            for(i = 0; i < groupRule.length; i++) {
                if(i%2==0) row = '<div class="row">';
                else row = "";
                if (i%2==0) endrow = '';
                else endrow = '</div>';
                wafDetail+=row + '<div class="col-sm-5" style="height: 40px;"><span title="'+groupRule[i].rule_description+'">'+groupRule[i].rule_name+'</span></div>';
                if(groupRule[i].rule_status==1) wafDetail+='<div class="col-sm-1" style="height: 40px;"><input style = "margin-left: 20px;" type="checkbox" checked data-toggle="toggle" class="toggle-button1" data-size="small" data-onstyle="primary" onchange="changeRuleStatus('+ groupRule[i].id +')"></div>'+endrow;
                else wafDetail+='<div class="col-sm-1" style="height: 40px;"><input style = "padding-left: 20px;" type="checkbox" data-toggle="toggle" class="toggle-button1" data-size="small" data-onstyle="primary" onchange="changeRuleStatus('+ groupRule[i].id +')"></div>'+endrow;
            }
            if (groupRule.length % 2 == 0) {
                wafDetail+='<button class="btn btn-default" style="float:right;" id="closeeeee" onclick="closeParent(this.id)">Close</button></td></tr>';
            } else {
                wafDetail+='</div><button class="btn btn-default" style="float:right;" id="closeeeee" onclick="closeParent(this.id)">Close</button></td></tr>';
            }
        },
    });

$(function() {
    $('.toggle-button1').bootstrapToggle();
  })
}

function changeRuleStatus(id) {
    var data = new FormData();
    data.append("id",id);
    $.ajax({
        method: "POST",
        url: "{{route('change-rule-status')}}",
        data: data,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData: false,
        contentType: false,
        success : function(a){
            // reloadGroupWebsiteTable();
            something_change = 1;
        },
    });
}

function editRule(id) {
    if(!$("#closeeeee").length) {
        tr = $("#editRule_"+id).parent().parent().parent()[0];
        getGroupRule(id);
        $(wafDetail).insertAfter(tr);
    }
}

function closeParent(id) {
    $("#"+id).parent().parent().remove();
}

$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});
</script>

@endsection