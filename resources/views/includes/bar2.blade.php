<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{ url('dashboard') }}">Web Application Firewall</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" 
aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-id="{{ url('dashboard') }}" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" >
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text" >Dashboard</span>
          </a>
        </li>
        <li class="nav-item" data-id="{{ url('group-website') }}" data-toggle="tooltip" data-placement="right" title="Group Websites">
          <a class="nav-link" >
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text" >Group Websites</span>
          </a>
        </li>
        <li class="nav-item" data-id="{{ url('website') }}" data-toggle="tooltip" data-placement="right" title="Websites">
          <a class="nav-link" >
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text" >Websites</span>
          </a>
        </li>
        <li class="nav-item" data-id="{{ url('group-rules') }}" data-toggle="tooltip" data-placement="right" title="Group Rules">
          <a class="nav-link" >
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text" >Group Rules</span>
          </a>
        </li>
        <li class="nav-item" data-id="{{ url('rules') }}" data-toggle="tooltip" data-placement="right" title="Rules">
          <a class="nav-link" >
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text" >Rules</span>
          </a>
        </li>
        <li class="nav-item" data-id="{{ url('waf') }}" data-toggle="tooltip" data-placement="right" title="WAF">
          <a class="nav-link" >
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text" >WAF</span>
          </a>
        </li>
        <li class="nav-item" data-id="{{ url('monitor') }}" data-toggle="tooltip" data-placement="right" title="Monitor">
          <a class="nav-link" >
            <i class="fa fa-fw fa-area-chart"></i>
            <span class="nav-link-text" >Monitor</span>
          </a>
        </li>
        <li class="nav-item" data-id="{{ url('security') }}" data-toggle="tooltip" data-placement="right" title="Security Checking System">
          <a class="nav-link" >
            <i class="fa fa-fw fa-expeditedssl"></i>
            <span class="nav-link-text" >Security Checking System</span>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item" style="margin-left:800px;">
          <a class="nav-link" data-toggle="modal" data-target="#restartModal">
            <i class="fa fa-fw fa-refresh"></i>Restart</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li>
      </ul>
    </div>
  </nav>
<script>
var something_change = 0;
</script>