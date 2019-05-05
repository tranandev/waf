<footer class="sticky-footer">
  <div class="container">
    <div class="text-center">
      <small>Copyright © Tran An</small>
    </div>
  </div>
</footer>
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fa fa-angle-up"></i>
</a>
<!-- Logout Modal-->
<div class="modal"><!-- Place at bottom of page --></div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-primary">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="restartModal" tabindex="-1" role="dialog" aria-labelledby="restartModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="restartModalLabel">Web service has been temporarily suspended and will resume momentarily.</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Restart" below if you are ready to restart your server.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a href="#" onclick="Restart()" class="btn btn-primary">Restart</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="detectModal" tabindex="-1" role="dialog" aria-labelledby="detectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detectModalLabel">Figure out something change.</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Restart" below if you are ready to restart your server.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a href="#" onclick="Restart()" class="btn btn-primary">Restart</a>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap core JavaScript-->
<!-- // <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- // <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/popper.min.js"></script>
<script src="vendor/bootstrap/js/tooltip.js"></script>
<script src="vendor/bootstrap/js/bootstrap.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/sb-admin.min.js"></script>
<script  src="table/datatables.js"></script>
<script  src="table/datatables.min.js"></script>
<script src="js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script>

  $('.nav-item').on('click', function (e) {
    if (something_change != 0 && $(this).attr('data-id') !== undefined){
      $("#detectModal").modal('show');
      var link = $(this).attr('data-id');
      $('#detectModal').on('hidden.bs.modal', function (e) {
        window.location.href = link;
      });
    } else if ($(this).attr('data-id') !== undefined){
      window.location.href = $(this).attr('data-id');
    }
  });

  function Restart() {
    $.ajax({
      method: "GET",
      url: "{{route('restart')}}",
      success : function(a){
        something_change = 0;
        if (a != 1) alert(a);
      },
    }); 
  }
</script>