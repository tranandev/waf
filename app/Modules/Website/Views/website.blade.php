@extends('layouts.app')
@section('content')
<style>
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 28px;
  }

  .switch input {display:none;}

  .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
  }

  .slider:before {
      position: absolute;
      content: "";
      height: 20px;
      width: 20px;
      left: 8px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
  }

  input:checked + .slider {
      background-color: #2196F3;
  }

  input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
      border-radius: 34px;
  }

  .slider.round:before {
      border-radius: 50%;
  }
  .upload-btn-wrapper {
      position: relative;
      overflow: hidden;
      display: inline-block;
  }

  .btn {
      border: 2px solid gray;
      color: gray;
      background-color: white;
      padding: 8px 20px;
      border-radius: 8px;
      font-size: 20px;
      font-weight: bold;
  }

  .upload-btn-wrapper input[type=file] {
      font-size: 100px;
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
  }
</style>
<div id='loading-container' class="loading-container"></div>
<div class="modal1"><!-- Place at bottom of page --></div>
<table id="WebsiteTable" class="table table-striped table-bordered thead-dark" style="width:100%">
    <thead>
        <tr>
            <th>Website</th>
            <th>IP</th>
            <th>Port</th>
            <th>Group Website</th>
            <th style="width: 60px;">SSL</th>
            <th style="width: 60px;">Edit</th>
        </tr>
    </thead>
</table>
<button class="btn btn-info" style="float:right; margin-top: 10px; margin-right:15px;" id="add" onclick="Append()"><i class="fa fa-plus" style="padding-right:10px;"></i><span class="glyphicon glyphicon-plus-sign"></span> Add New</button>
<script>

    var isEdit = false;
    var table;
    var check_ssl;
    $(document).ready(function() {
        table = $('#WebsiteTable').DataTable( {
            lengthChange: false,
            ajax: "{{route('get-website-dataTable')}}",
            columns: [
            { data: "name" },
            { data: "ip" },
            { data: "port_listen"},
            { data: null, render: function ( data, type, row ) {
                return row.groupwebsite.name;
            } },
            { data: null, render: function ( data, type, row ) {
                if (row.ssl == 0) return 'Off';
                else  return 'On' ;
            } },
            { data: null, render: function ( data, type, row ) {
                return '<i style="margin-left:13px;" class="fa fa-lg fa-edit" title="Edit" aria-hidden="true" id="editWebsite_'+ data.id +'" onclick="Edit(\''+ data.id +'\')"></i><i style="margin-left: 20px;" class="fa fa-lg fa-trash-o" title="Delete" aria-hidden="true" id="deleteWebsite_'+ data.id +'" onclick="Delete(\''+ data.id +'\')"></i>';
            } }
            ],
            select: true
        } );
    });

    function Append() {
        tr = $(".fa-check");
        if (!tr.length){
            $('#WebsiteTable').append('<tr id="WebsiteAppend" role="row" class="even"><td class="sorting_1"><input id="addWebsite" style="width: 100%;" value=""></td><td><input id="addWebsiteIP_Port" style="width: 100%;" value=""></td><td><input id="addWebsiteListen_Port" style="width: 100%;" value=""></td><td><select id="addGroupWebsite"> <option></option>' + '<?php foreach ($group_website as $value) {echo "<option value=\"$value->id\">$value->name</option>";} ?>'+'</select></td><td><label class="switch"><input id="websitessl" type="checkbox" onchange="changeSSL()"><span class="slider round"></span></label></td><td><i style="margin-left:13px;" class="fa fa-lg fa-check" title="Save" aria-hidden="true" id="saveWebsite" onclick="Save()"></i><i style="margin-left: 20px;" class="fa fa-lg fa-close" title="Delete" aria-hidden="true" id="deleteWebsite" onclick="reloadWebsiteTable()"></i></td></tr>');    
        }
    }

    function reloadWebsiteTable() {
        table.ajax.reload(null, false );
    }

    function Edit(id) {
        var data = new FormData();
        id_num = id;
        data.append('id', id_num);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: "{{route('check-rule')}}",
            data: data,
            processData: false,
            contentType: false,
            success : function(a){
                if (a == 1) check_ssl = 1;
                else check_ssl = 0;
            // reloadWebsiteTable();
            console.log(check_ssl);
        },
    });
        check = $('.fa-check');
        if (!check.length){
            $("#editWebsite_"+id).toggleClass('fa-edit fa-check');
            $("#deleteWebsite_"+id).toggleClass('fa-trash-o fa-close');
            $("#editWebsite_"+id).attr("onclick", "Update(\'"+ id +"\')");
            $("#deleteWebsite_"+id).attr("onclick", "reloadWebsiteTable()");
            var column = $("#editWebsite_"+id).parent().parent().children();
            for (i = 0; i < column.length - 3; i++) {
                value = column[i].innerText;
                column[i].innerHTML="<input id='input_data_"+ (i+1) +"' style='width: 100%;' value='" + value + "'>";
            }
            column[column.length-3].innerHTML='<select id="addGroupWebsite" style="min-width: 100%;"><option></option>' + '<?php foreach ($group_website as $value) {echo "<option id=\"select$value->id\" value=\"$value->id\">$value->name</option>";} ?>'+'</select>';
            ssl_check = column[column.length-2].innerText;
            if (ssl_check == 'Off') {
                column[column.length-2].innerHTML='<label class="switch"><input id="websitessl" type="checkbox" onchange="changeSSL()"><span class="slider round"></span></label>';
            } else {
                column[column.length-2].innerHTML='<label class="switch"><input id="websitessl" type="checkbox" onchange="changeSSL()" checked><span class="slider round"></span></label>';
                tr = $("#websitessl").parent().parent().parent()[0];
                sslDetail = '<tr id="waf-detail"><td colspan="6"><div class="row"><div class="col-sm-2"><div class="upload-btn-wrapper"><button style="background-color:#2344;" class="btn btnKey">Key File</button><input id="webKeyFile" type="file" onchange="changeKey()" name="myKeyFile" /></div></div><div class="col-sm-2"><div class="upload-btn-wrapper"><button style="background-color:#2344;" class="btn btnCert">Cert File</button><input id="webCertFile" type="file" onchange="changeCert()" name="myfile" /></div></div></div></td></tr>'
                $(sslDetail).insertAfter(tr);
            }
        }
    }

    function changeCert() {
        if($("#webCertFile").val()) $(".btnCert").css('background-color', '#2344')
    }

function changeKey() {
    if($("#webKeyFile").val()) $(".btnKey").css('background-color', '#2344')
}

function changeSSL() {
    ssl = $('#websitessl').prop("checked")?1:0;
    if (!ssl) {
        $('#waf-detail').remove();
    }else{
        tr = $("#websitessl").parent().parent().parent()[0];
        sslDetail = '<tr id="waf-detail"><td colspan="6"><div class="row"><div class="col-sm-2"><div class="upload-btn-wrapper"><button class="btn btnKey">Key File</button><input id="webKeyFile" type="file" onchange="changeKey()" name="myKeyFile" /></div></div><div class="col-sm-2"><div class="upload-btn-wrapper"><button class="btn btnCert">Cert File</button><input id="webCertFile" type="file" onchange="changeCert()" name="myfile" /></div></div></div></td></tr>'
        $(sslDetail).insertAfter(tr);
        $('#input_data_3').attr("value", "443");
    }
}

function Save() {
    var data = new FormData();
    name = $('#addWebsite').val();
    ip = $('#addWebsiteIP_Port').val();
    listen_port = $('#addWebsiteListen_Port').val();
    group_website = $('#addGroupWebsite').val();
    ssl = $('#websitessl').prop("checked")?1:0;
    console.log(ssl);
    if (ssl) {
        key = $("#webKeyFile").prop('files')[0];
        cert = $("#webCertFile").prop('files')[0];
        if (!$("#webCertFile").val() || !$("#webKeyFile").val()) {
            alert('Please upload key file and cert file!!!');
            return 0;
        }
        data.append('key', key);
        data.append('cert', cert);
    }
    data.append('ssl', ssl);
    data.append('name', name);
    data.append('ip', ip);
    data.append('listen_port', listen_port);
    data.append('group_website', group_website);
    console.log(name);
    console.log(ip);
    console.log(listen_port);
    console.log(group_website);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: "{{route('add-website')}}",
        data: data,
        processData: false,
        contentType: false,
        success : function(a){
            if (a != 1) alert(a); 
            reloadWebsiteTable();
            something_change = 1;
        },
    });
}


function Update(id) {
    var data = new FormData();
    name = $("#input_data_1").val();
    ip = $("#input_data_2").val();
    listen_port = $("#input_data_3").val();
    group_website = $('#addGroupWebsite').val();
    ssl = $('#websitessl').prop("checked")?1:0;
    id_num = id;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if (ssl) {
        if (check_ssl == 1) {
            data.append('key_check', 1);
            data.append('cert_check', 1);
            data.append('ssl', ssl);
            $("#editWebsite_"+id).toggleClass('fa-check fa-edit');
            $("#deleteWebsite_"+id).toggleClass('fa-close fa-trash-o');
            $("#editWebsite_"+id).attr("onclick", "Edit(\'"+ id +"\')");
            $("#deleteWebsite_"+id).attr("onclick", "Delete(\'"+ id +"\')");
            data.append('id', id_num);
            data.append('name', name);
            data.append('ip', ip);
            data.append('listen_port', listen_port );
            data.append('group_website', group_website);
            console.log(group_website);
            $.ajax({
                method: "POST",
                url: "{{route('update-website')}}",
                data: data,
                processData: false,
                contentType: false,
                success : function(a){
                    if (a != 1) alert(a); 
                    reloadWebsiteTable();
                    something_change = 1;
                },
            });
        } else {
            key = $("#webKeyFile").prop('files')[0];
            cert = $("#webCertFile").prop('files')[0];
            if (!$("#webCertFile").val() || !$("#webKeyFile").val()) {
                alert('Please upload key file and cert file!!!');
            } else {
                data.append('key', key);
                data.append('cert', cert);
                data.append('ssl', ssl);
                $("#editWebsite_"+id).toggleClass('fa-check fa-edit');
                $("#deleteWebsite_"+id).toggleClass('fa-close fa-trash-o');
                $("#editWebsite_"+id).attr("onclick", "Edit(\'"+ id +"\')");
                $("#deleteWebsite_"+id).attr("onclick", "Delete(\'"+ id +"\')");
                data.append('id', id_num);
                data.append('name', name);
                data.append('ip', ip);
                data.append('listen_port', listen_port );
                data.append('group_website', group_website);
                console.log(group_website);
                $.ajax({
                    method: "POST",
                    url: "{{route('update-website')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    success : function(a){
                        if (a != 1) alert(a); 
                        reloadWebsiteTable();
                        something_change = 1;
                    },
                });
            } 
        }
    } else {
        $("#editWebsite_"+id).toggleClass('fa-check fa-edit');
        $("#deleteWebsite_"+id).toggleClass('fa-close fa-trash-o');
        $("#editWebsite_"+id).attr("onclick", "Edit(\'"+ id +"\')");
        $("#deleteWebsite_"+id).attr("onclick", "Delete(\'"+ id +"\')");
        data.append('id', id_num);
        data.append('name', name);
        data.append('ip', ip);
        data.append('listen_port', listen_port );
        data.append('group_website', group_website);
        console.log(group_website);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: "{{route('update-website')}}",
            data: data,
            processData: false,
            contentType: false,
            success : function(a){
                if (a != 1) alert(a); 
                reloadWebsiteTable();
                something_change = 1;
            },
        });
    }
}

function Delete(id) {
    check = $('.fa-check');
    if (!check.length){
        $("#editWebsite_"+id).toggleClass('fa-edit fa-check');
        $("#deleteWebsite_"+id).toggleClass('fa-trash-o fa-close');
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
                url: "{{route('delete-website')}}",
                data: data,
                processData: false,
                contentType: false,
                success : function(a){
                    if (a != 1) alert(a); 
                    reloadWebsiteTable();
                    something_change = 1;
                },
            });
        }
        reloadWebsiteTable();
    }
}


// $(document).on({
//     ajaxStart: function() { $("#loading-container").css('display', 'block'); console.log('aksjdfh') ;  },
//      ajaxStop: function() { $("#loading-container").css('display', 'none'); console.log('uiiuyiuyt') ; }    
// });

$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
    ajaxStop: function() { $body.removeClass("loading"); }    
});

</script>

@endsection