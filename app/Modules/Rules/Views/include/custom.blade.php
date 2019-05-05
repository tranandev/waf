<header class="panel-heading" data-toggle="collapse" href="#RuleTableCustom"  aria-expanded="false" aria-controls="RuleTable" style="margin-left: 20px; margin-top:50px; margin-right: 1px; height: 50px; background-color:#343a40; border-top-left-radius: 5px; border-top-right-radius: 5px;">
<i class="fa fa-globe" style="margin-top:17px; margin-left: 10px; color: white;">  CUSTOM RULE</i>
</header>
<div class="collapse multi-collapse" id="RuleTableCustom" style="overflow:auto; margin-top: 10px;">
<table id="RuleTablecustom" class="table table-striped table-bordered thead-dark" style="width:98%; margin-left: 20px;">
        <thead>
            <tr>
                <th id="tbrule_c">Rule</th>
                <th id="tbdes_c">Description</th>
                <th id="tbgr_rule_c">Group Rule</th>
                <th id="tbedit_c">Edit</th>
            </tr>
        </thead>
    </table>
<button class="btn btn-info" style="float:right; margin-top: 10px; margin-right:15px;" id="add" onclick="AppendCustom()"><i class="fa fa-plus" style="padding-right:10px;"></i><span class="glyphicon glyphicon-plus-sign"></span> Add New</button>
</div>

<script>
var isEdit = false;
var table_custom;
$(document).ready(function() {
        table_custom = $('#RuleTablecustom').DataTable( {
        lengthChange: false,
        ajax: "{{route('get-custom-dataTable')}}",
        columns: [
            { data: "rule" },
            { data: "description" },
            { data: null, render: function ( data, type, row ) {
                return row.grouprulecustom.name;
            } },
            { data: null, render: function ( data, type, row ) {
                return '<i style="margin-left:13px;" class="fa fa-lg fa-edit" title="Edit" aria-hidden="true" id="editCustom_'+ data.id +'" onclick="EditCustom(\''+ data.id +'\')"></i><i style="margin-left: 20px;" class="fa fa-lg fa-trash-o" title="Delete" aria-hidden="true" id="deleteCustom_'+ data.id +'" onclick="DeleteCustom(\''+ data.id +'\')"></i>';
            } }
        ],
        select: true,
        "initComplete": function(settings, json) {
            $("#tbedit_c").css('width', '60');
            // $("#tbdes_c").css('width', '60');
            // $("#tbrule_c").css('width', '100');
            // $("#tbgr_rule_c").css('width', '60');
        }
    } );
});

function AppendCustom() {
    tr = $(".fa-check");
    if (!tr.length){
        $('#RuleTablecustom').append('<tr id="CUSTOMRuleAppend" role="row" class="even"><td class="sorting_1"><textarea id="addCustomRule" style="width: 520px;"></textarea></td><td><input id="addCustomRuleDescription" style="width: 100%;" value=""></td><td><select id="addGroupRuleCustom"> <option></option>' + '<?php foreach ($group_rule as $value) {echo "<option value=\"$value->id\">$value->name</option>";} ?>'+'</select></td><td><i style="margin-left:13px;" class="fa fa-lg fa-check" title="Save" aria-hidden="true" id="saveCustomRule" onclick="SaveCustom()"></i><i style="margin-left: 20px;" class="fa fa-lg fa-close" title="Delete" aria-hidden="true" id="deleteCustomRule" onclick="reloadCustomRuleTable()"></i></td></tr>');    
    }
}

function reloadCustomRuleTable() {
    value = [];
    table_custom.ajax.reload(null, false );
}

var value=[];

function EditCustom(id) {
    check = $('.fa-check');
    if (!check.length){
    $("#editCustom_"+id).toggleClass('fa-edit fa-check');
    $("#deleteCustom_"+id).toggleClass('fa-trash-o fa-close');
    $("#editCustom_"+id).attr("onclick", "UpdateCustom(\'"+ id +"\')");
    $("#deleteCustom_"+id).attr("onclick", "reloadCustomRuleTable()");
    var column = $("#editCustom_"+id).parent().parent().children();
    value[0] = $(column[0]).text();
    console.log(value);
        column[0].innerHTML='<textarea id="textRule" name="comment" style="width: 520px;">' + value[0] + '</textarea>';
    for (i = 1; i < column.length - 2; i++) {
        value[i] = column[i].innerText;
        column[i].innerHTML="<input id='input_data_"+ (i+1) +"' style='width: 100%;' value='" + value[i] + "'>";
    }
    value[column.length - 2] = column[column.length - 2].innerText;
    column[column.length - 2].innerHTML='<td><select id="addGroupRuleCustom"> <option></option>' + '<?php foreach ($group_rule as $value) {echo "<option value=\"$value->id\">$value->name</option>";} ?>'+'</select></td>';
}
}



function SaveCustom() {
    rule = $('#addCustomRule').val();
    description = $('#addCustomRuleDescription').val();
    group_rule = $('#addGroupRuleCustom').val();
    var data = new FormData();
    data.append('rule', rule);
    data.append('description', description);
    data.append('group_rule', group_rule);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: "{{route('add-custom-rule')}}",
        data: data,
        processData: false,
        contentType: false,
        success : function(a){
            if (a != 1) alert(a);
            reloadCustomRuleTable();
            something_change = 1;
            $("#tbedit_c").css('width', '60');
        },
    });
}

function UpdateCustom(id) {
    rule = $("#textRule").val();
    description = $("#input_data_2").val();
    group_rule = $('#addGroupRuleCustom').val();
    id_num = id;
    $("#editCustom_"+id).toggleClass('fa-check fa-edit');
    $("#deleteCustom_"+id).toggleClass('fa-close fa-trash-o');
    $("#editCustom_"+id).attr("onclick", "EditCustom(\'"+ id +"\')");
    $("#deleteCustom_"+id).attr("onclick", "DeleteCustom(\'"+ id +"\')");
    var data = new FormData();
    data.append('id', id_num);
    data.append('rule', rule);
    data.append('description', description);
    data.append('group_rule', group_rule);
    data.append('old_rule', value[0]);
    data.append('old_description', value[1]);
    data.append('old_group_rule', value[2]);
    // console.log(id_num);
    // console.log(rule);
    // console.log(description);
    // console.log(group_rule);
    // console.log(value[0]);
    // console.log(value[1]);
    // console.log(value[2]);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST",
        url: "{{route('update-custom-rule')}}",
        data: data,
        processData: false,
        contentType: false,
        success : function(a){
            if (a != 1) alert(a);
            reloadCustomRuleTable();
            value = [];
            something_change = 1;
            $("#tbedit_c").css('width', '60');
        },
    });
}

function DeleteCustom(id) {
    check = $('.fa-check');
    if (!check.length){
    $("#editCustom_"+id).toggleClass('fa-edit fa-check');
    $("#deleteCustom_"+id).toggleClass('fa-trash-o fa-close');
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
        url: "{{route('delete-custom-rule')}}",
        data: data,
        processData: false,
        contentType: false,
        success : function(a){
            if (a != 1) alert(a);
            reloadCustomRuleTable();
            something_change = 1;
            $("#tbedit_c").css('width', '60');
        },
    });
    }
    reloadCustomRuleTable();
    }
}
</script>