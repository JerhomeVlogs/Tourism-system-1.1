<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <x-header data="Dashboard | Tourism "/>
  <title>Bolinao Tourism Office | Office Access</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

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

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="#" class="logo d-flex align-items-center">
        <img src="/home/img/logo_icon.png" alt="logo">
        <span class="d-none d-lg-block">Tourism Monitoring</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">


       
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('storage/img/'. $user_data['img_name']) }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user_data['first_name'] }} {{ $user_data['last_name'] }}</span>
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

          </ul>
        </li>

      </ul>
    </nav>

  </header>
