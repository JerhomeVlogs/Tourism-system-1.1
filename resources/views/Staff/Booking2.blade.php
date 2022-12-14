@include('inc.staff-header');
  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="{{route('book.view')}}">
          <i class="bi bi-car-front-fill"></i>
          <span>Add</span>
        </a>
      </li><!-- End Dashboard Nav -->

    @include('inc.staff-sidebar')
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Add</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('book.view')}}">Check Point</a></li>
          <li class="breadcrumb-item active">Add</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="container  h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-lg-100 mb-4 mb-lg-0">
              <div class="card mb-3" style="border-radius: .5rem;">
                <div class="row g-0">
                  <div class="col-md-40">
                    <div class="card-body p-4">
                        <h6>Information</h6>
                      <hr class="mt-0 mb-4">
                      <div class="row pt-1">
                        <div class="col-lg-20 mx-auto">
                          <div class="card">
                            <div class="card-header">
                              <h4>Add</h4>
                            </div>
                            <div class="card-body p-4">
                              <form action="{{ route('staff.book.data') }}" method="post" id="add_form">
                                @csrf
                                <div class="row">
                                  <div class="row" id="solo_book">
                                    <input type="hidden" name="solo" value="solo">
                                  </div>
                                  <div class="col-md-4 mb-1">
                                    <select name="destination" id="locations" class="form-select" aria-label="Default select example" required>
                                      <option value="">Please Wait</option>
                                    </select>
                                    <x-error_style/>@error('password') {{$message}} @enderror</p>
                                  </div>
                                  <div class="col-md -1mb-1 d-flex align-middle">
                                    <p>Est. Time to Leave</p>
                                    <div class="col-md-4 mb-1">
                                      <input type="time" name="time_leave" required>
                                      <x-error_style/>@error('time_leave') {{$message}} @enderror</p>
                                    </div>
                                  </div>
                                  <hr class="mt-2 mb-3"/>   
                                </div>
                                <div class="row">
                                  <div class="mb-1">
                                    <h4>User Information</h4>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-4 mb-3">
                                      <input type="text" name="first_nameUser" class="form-control" placeholder="Fist Name" minlength="3" required>
                                    
                                    </div>
                                    <div class="col-md-4 mb-3">
                                      <input type="text" name="last_nameUser" class="form-control" placeholder="Last Name" minlength="3" required>
                                    
                                    </div><div class="col-md-4 mb-3">
                                      <select name="genderUser" class="form-select" aria-label="Default select example" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                      </select>
                                    </div>  
                                  </div>
                                  <div class="row">
                                    <div class="col-md-4 mb-3">
                                      <input type="email" name="emailUser" class="form-control" placeholder="Email" minlength="3" required>
                                    
                                    </div>
                                    <div class="col-md-4 mb-3">
                                      <input type="number" name="phoneUser" class="form-control" placeholder="Phone" maxlength="12">
                                    
                                    </div><div class="col-md-4 mb-3">
                                      <input type="text" name="addressUser" class="form-control" placeholder="Address" minlength="3" required>
                                    </div>
                                    <hr class="mt-2 mb-3"/>   
                                  </div>
                                </div>
                                <div id="show_item">
                                  <div class="row">
                                    <div class="row">
                                      <div class="col-md-2 mb-3 ml-3 d-grid">
                                        <button type="button" class="btn btn-success add_item_btn">Add more</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div>
                                  <input type="submit" value="Add" class="btn btn-primary w-25 request_book" id="add_btn">
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Bolinao Tourism</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Designed by <a href="www.bolinao.tourism.com">Bolinao Tourism</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  {{-- jquery cdn --}}
  <script src="/home/vendors/jquery/jquery-3.2.1.min.js"></script>
  {{-- <script src="/user/assets/js/add_rows.js"></script> --}}
  <script src="/user/assets/js/add_rows.js"></script>
  <script src="/user/assets/js/all.js"></script>
  <script src="/user/assets/js/send_notification.js"></script>

 

  <!-- Vendor JS Files -->
  <script src="/user/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="/user/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/user/assets/vendor/chart.js/chart.min.js"></script>
  <script src="/user/assets/vendor/echarts/echarts.min.js"></script>
  <script src="/user/assets/vendor/quill/quill.min.js"></script>
  <script src="/user/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/user/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="/user/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="/user/assets/js/main.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"></script>

</body>

</html>