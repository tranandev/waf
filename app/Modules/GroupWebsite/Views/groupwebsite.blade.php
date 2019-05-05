@extends('layouts.app')
@section('content')
<div class="modal1"><!-- Place at bottom of page --></div>
<table id="GroupWebsiteTable" class="table table-striped table-bordered thead-dark" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th style="width: 60px;">Edit</th>
            </tr>
        </thead>
    </table>
    <button class="btn btn-info" style="float:right; margin-top: 10px; margin-right:15px;" id="add" onclick="Append()"><i class="fa fa-plus" style="padding-right:10px;"></i><span class="glyphicon glyphicon-plus-sign"></span> Add New</button>
<script>

var isEdit = false;
var table;
$(document).ready(function() {
        table = $('#GroupWebsiteTable').DataTable( {
        lengthChange: false,
        ajax: "{{route('get-dataTable')}}",
        columns: [
            { data: "name" },
            { data: "description" },
            { data: null, render: function ( data, type, row ) {
                return '<i style="margin-left:13px;" class="fa fa-lg fa-edit" title="Edit" aria-hidden="true" id="editGroupWebsite_'+ data.id +'" onclick="Edit(\''+ data.id +'\')"></i><i style="margin-left: 20px;" class="fa fa-lg fa-trash-o" title="Delete" aria-hidden="true" id="deleteGroupWebsite_'+ data.id +'" onclick="Delete(\''+ data.id +'\')"></i>';
            } }
        ],
        select: true
    } );
});

function Append() {
    tr = $(".fa-check");
    if (!tr.length){
        $('#GroupWebsiteTable').append('<tr id="GroupWebsiteAppend" role="row" class="even"><td class="sorting_1"><input id="addGroupWebsiteName" style="width: 100%;" value=""></td><td><input id="addGroupWebsiteDescription" style="width: 100%;" value=""></td><td><i style="margin-left:13px;" class="fa fa-lg fa-check" title="Save" aria-hidden="true" id="saveGroupWebsite" onclick="Save()"></i><i style="margin-left: 20px;" class="fa fa-lg fa-close" title="Delete" aria-hidden="true" id="deleteGroupWebsite" onclick="reloadGroupWebsiteTable()"></i></td></tr>');    
    }
}

function reloadGroupWebsiteTable() {
    table.ajax.reload(null, false );
}

function Edit(id) {
    check = $('.fa-check');
    if (!check.length){
    $("#editGroupWebsite_"+id).toggleClass('fa-edit fa-check');
    $("#deleteGroupWebsite_"+id).toggleClass('fa-trash-o fa-close');
    $("#editGroupWebsite_"+id).attr("onclick", "Update(\'"+ id +"\')");
    $("#deleteGroupWebsite_"+id).attr("onclick", "reloadGroupWebsiteTable()");
    var column = $("#editGroupWebsite_"+id).parent().parent().children();
    for (i = 0; i < column.length - 1; i++) {
        value = column[i].innerText;
        column[i].innerHTML="<input id='input_data_"+ (i+1) +"' style='width: 100%;' value='" + value + "'>";
    }
}
}



function Save() {
    name = $('#addGroupWebsiteName').val();
    description = $('#addGroupWebsiteDescription').val();
    var data = new FormData();
    data.append('name', name);
    data.append('description', description);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: "{{route('add-group-website')}}",
        data: data,
        processData: false,
        contentType: false,
        success : function(a){
            if (a != 1) alert(a);    
            reloadGroupWebsiteTable();
            something_change = 1;
        },
    });
}

function Update(id) {
    name = $("#input_data_1").val();
    description = $("#input_data_2").val();
    id_num = id;
    $("#editGroupWebsite_"+id).toggleClass('fa-check fa-edit');
    $("#deleteGroupWebsite_"+id).toggleClass('fa-close fa-trash-o');
    $("#editGroupWebsite_"+id).attr("onclick", "Edit(\'"+ id +"\')");
    $("#deleteGroupWebsite_"+id).attr("onclick", "Delete(\'"+ id +"\')");
    var data = new FormData();
    data.append('id', id_num);
    data.append('name', name);
    data.append('description', description);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: "{{route('update-group-website')}}",
        data: data,
        processData: false,
        contentType: false,
        success : function(a){
            if (a != 1) alert(a);
            reloadGroupWebsiteTable();
            something_change = 1;
        },
    }); 
}

function Delete(id) {
    check = $('.fa-check');
    if (!check.length){
    $("#editGroupWebsite_"+id).toggleClass('fa-edit fa-check');
    $("#deleteGroupWebsite_"+id).toggleClass('fa-trash-o fa-close');
    con = confirm ("Are you sure????");
    if (con == true){
        id_num = id;
        var data = new FormData();
        data.append('id', id_num);
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: "{{route('delete-group-website')}}",
        data: data,
        processData: false,
        contentType: false,
        success : function(a){
            if (a != 1) alert(a);
            reloadGroupWebsiteTable();
            something_change = 1;
        },
    });
    }
    reloadGroupWebsiteTable();
    }
}

$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});
</script>

@endsection