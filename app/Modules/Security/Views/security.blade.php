 @extends('layouts.app')
@section('content') 
  <div class="modal1"><!-- Place at bottom of page --></div>
  <div class="row" style="margin-bottom:20px;" >
  <label style="margin-left:40px; margin-top:10px;" for="ip">Host: </label>
  <select id="addGroupWebsite" style="margin-left:20px;"> 
    <option></option>
    <?php foreach ($website as $value) {echo "<option value=\"$value->ip\" data=\"$value->ip\">$value->name</option>";} ?>
  </select>
  </div>
    <div class="row" style="margin-left:26px; margin-right:20px; padding-bottom: 10px;">
      <div id="list_error" class="col-md-9 col-sm-9" style="border: 2px solid black; border-radius: 5px; background-color:#A6A69B; max-height: 500px; min-height: 500px; overflow-y: scroll; padding: 0px;">
      </div>
      <div id="error_content" class="col-md-3 col-sm-3" style="border: 2px solid red; border-radius: 5px; background-color:#DCDCDC; max-height: 500px; min-height: 500px; padding: 0px; ">
         <div class="col-md-12 col-sm-12" style="color: #e00e0e; padding-top: 10px;">
          WARNING INFORMATION!!!
         </div>
      </div>
    </div>

    <div class="row" style="margin-left:26px; margin-right:20px; padding-bottom: 30px;">
      <div id="error_des" class="col-md-9 col-sm-9" style="background-color:#A6A69B; border: 2px solid black; border-radius: 5px; max-height: 200px; min-height: 200px; overflow-y: scroll; padding: 0px;">
       <div class="col-md-12 col-sm-12" style="color: #000000; padding-top: 10px;">
          Description of vulnerability.
        </div>
      </div>

      <div id="error_total" class="col-md-3 col-sm-3" style="background-color:#DCDCDC; border: 2px solid black; border-radius: 5px; max-height: 200px; min-height: 200px; padding: 0px; ">
      </div>
    </div>

    <div class="row" style="margin-left:26px; margin-right:20px; padding-bottom: 30px;">
      <div class="col-md-6 col-sm-6" style="text-align: center;">
        <button id="button1" type="button" class="btn btn-success btn-lg" onclick="checkErrors()" style="background-color:#333;">Check</button>
      </div>
      <div class="col-md-6 col-sm-6" style="text-align: center;">
        <button id="button2" type="button" class="btn btn-success btn-lg" onclick="fixErrors()" style="background-color:#333;">Fix</button>
      </div>
    </div>

<script> 
var Errors;
var checked = false;
var array=[];

$('select').on('change', function() {
  // alert( this.value );
  $("#button1").attr("onclick", "checkErrors(\""+ this.value +"\")");
  $("#button2").attr("onclick", "fixErrors(\""+ this.value +"\")");
})

function checkErrors(ip){
  $.ajax({ 
    method:"GET", 
    url: "/check?ip="+ip,
    // url: "/check",
    // headers:  {'Access-Control-Allow-Origin' : 'http://192.168.218.138'},
    success: function (data) {
      var allData = JSON.parse(data);
      Errors = allData;
      $("#list_error").empty();
      $("#error_content").empty();
      $("#error_des").empty();
      $("#error_total").empty();
      array=[];
      for (i = 0; i < allData.length; i++){
        $("#list_error").append('<div id="Error'+ i +'" class="col-md-12 col-sm-12" style="background-color:#333; color: #FFFFFF; border: 2px solid white; border-radius: 5px;" data-id="'+ i +'" onclick="onclickFunction(this.id)"><div class="row"><div class="col-md-6 colsm-6" style="margin-top:10px; margin-bottom:10px;"><h8>'+ allData[i].name +'</h8></div><div class="col-md-4 colsm-4" style="margin-top:10px; margin-bottom:10px;"><h9>'+ allData[i].group_error +'</h9></div><div class="col-md-1 colsm-1" style="text-align: center; padding-left: 95px; padding:5px;"><div class="checkbox" style="padding-left: 50px"><label><input type="checkbox" value="" data-id="'+ allData[i].id +'" class="checkbox_check"'+ allData[i].id +'"></label></div></div></div></div>');
      }
      $("#error_total").append('<div class="col-md-12 col-sm-12" style="color: #e00e0e; padding-top: 10px;"><h3>Statistics</h3></div><div class="col-md-12 col-sm-12" style="color: #e00e0e; padding-top: 10px;">Alert: '+ Errors.length +'</div><div class="col-md-12 col-sm-12" style="color: #e00e0e; padding-top: 10px;">Total: 54</div>');
      checked = true;
    }
});
}

function onclickFunction(div_id){
  error_id = $("#"+div_id).attr("data-id");
  $("#error_content").empty();
  $("#error_content").append('<div class="col-md-12 col-sm-12" style="padding-top: 10px; color: #FF3030;"><h3>Detail</h3></div>');
  for (i = 1; i < Errors[error_id].l_error.length; i++){
    $("#error_content").append('<div class="col-md-12 col-sm-12" style="padding-top: 10px; color: #FF3030;">'+ Errors[error_id].l_error[i] +'</div>');
  }
  $("#error_des").empty();
  $("#error_des").append('<div class="col-md-12 col-sm-12" style="padding-top: 10px; color: #000000;"><h3>Description</h3></div><div class="col-md-12 col-sm-12" style="padding-bottom: 10px; color: #000000;">'+ Errors[error_id].des +'</div>');
}


function fixErrors(ip){
  if (checked == false){
    alert('You must check errors before!!!');
    return;
  }
  $.ajaxSetup({
        headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
  });
  if ($("input:checked").length != 0){
  $("input:checked").each(function(index){
  array.push($(this).attr("data-id"));
});
  $.ajax({ 
    type: 'POST', 
    url: '/fix?ip='+ip+'&token='+$('meta[name="csrf-token"]').attr('content'),
    data: {id : array},
    success: function (response) {
      if (response != 1) console.log(response);
    }
});
}else{
  $.ajax({ 
    type: 'GET', 
    url: '/fix-all?ip='+ip,
    success: function (response) {
      if (response != 1) console.log(response);
    }
});
}
checked = false;
}


$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});
</script>

@endsection
