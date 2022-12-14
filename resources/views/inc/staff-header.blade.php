<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <x-header data="Dashboard | Tourism "/>
  <title>Tourism Office | Staff Access</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- data table cdn --}}
  <link href="/user/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="/user/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <!-- Favicons -->
  <link href="/user/assets/img/favicon.png" rel="icon">
  <link href="/user/assets/img/apple-touch-icon.png" rel="apple-touch-icon">
 

   <!-- Icon Font Stylesheet -->
   <link rel="stylesheet" href="/home/vendors/fontawesome/css/all.min.css">
   <link href="/user/assets/css/bootstrap-icon.css" rel="stylesheet">

    <!-- Vendor CSS Files -->
  <link href="/user/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/user/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/user/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/user/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="/user/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="/user/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/user/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="/user/assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css">

</head>

<body>

   <!-- Modal add location -->
   <div class="modal fade" id="staff_viewnotif" tabindex="-1" aria-labelledby="viewnotif" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Notification from <span id="staff_sender"></span></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="staff_add_success"></div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <h4>Notification Level: <span id="staff_type"></span></h4>
            </div>
            <div class="mb-3">
              <p id="staff_message"></p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    @if (Session::get('staff_click'))
    <div class=" mb-2">
      <input type="hidden" class="staff_view_click" value="{{ Session::get('staff_click') }}">
    </div>
    @endif
    <div class="d-flex align-items-center justify-content-between">
      <a href="#" class="logo d-flex align-items-center">
        <img src="/home/img/logo_icon.png" alt="logo">
        <span class="d-none d-lg-block">Tourism Monitoring</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="" id="staff_notif_count"></span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              Notifications
              <a href="/staff/view/all"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <div id="staff_notif">
            </div>

            <li class="dropdown-footer">
              <a href="/staff/view/all" >Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items --> 

        </li><!-- End Notification Nav -->

        
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('storage/img/'. $user_data['img_name']) }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ strtok($user_data['first_name']," ") }} {{ $user_data['last_name'] }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ $user_data['first_name'] }} {{ $user_data['last_name'] }}</h6>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

           
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                  <i class="bi bi-box-arrow-right"></i>
                  <span>Logout</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
