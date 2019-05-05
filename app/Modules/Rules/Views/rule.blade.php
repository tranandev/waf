@extends('layouts.app')
@section('content')
<div class="modal1"><!-- Place at bottom of page --></div>
@include('Rules::include.ip')
@include('Rules::include.url')
@include('Rules::include.custom')
<script>
$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});

</script>
@endsection