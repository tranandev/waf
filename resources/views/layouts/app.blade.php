<!DOCTYPE html> 
<html lang="en"> 
<head>
  @include('includes.header') 
</head> 

  <body class="inverse-nav sticky-footer bg-light" >
  <!-- Navigation-->
  @include('includes.bar')
    <div class="" ">
      @yield('content')
      <!-- /.container-fluid-->
      <!-- /.content-wrapper-->
      @include('includes.footer')
    </div> 
  </body> 
</html>
