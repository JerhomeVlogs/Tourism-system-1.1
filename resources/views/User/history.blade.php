@include('inc.user-header');
  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="dashboard">
          <i class="bi bi-grid"></i>
          <span>Request History</span>
        </a>
      </li><!-- End Dashboard Nav -->

    @include('inc.user-sidebar')
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Logs</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Logs</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="container">
      <div class="container">
        <div class="row">
          <div class="col-12 table-responsive" id="no-more-table">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">Ticket number</th>
                  <th scope="col">Name</th>
                  <th scope="col">Gender</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Email</th>
                  <th scope="col">Address</th>
                  <th scope="col">Destination</th>
                  <th scope="col">Groups</th>
                  <th scope="col">Date & Time</th>
                </tr>
              </thead>
                <tbody>
                  @foreach($lists as $list)
                  <tr>
                    <td data-title="Book Number">{{ $list->book_number }}</td>
                    <th data-title="Name">{{ $list->first_name }} {{ $list->last_name }}</th>
                    <td data-title="Gender">{{ $list->gender }}</td>
                    <td data-title="Phone">{{ $list->phone }}</td>
                    <td data-title="Email">{{ $list->email }}</td>
                    <td data-title="Address">{{ $list->address }}</td>
                    <td data-title="Destination">{{ $list->destination }}</td>
                    <td data-title="Groups">{{ $list->groups }} 
                      @if ($list->groups != "0")
                      <a href="/user/log/view/all?id={{ $list->book_number }}" class="btn btn-primary"><i class="far fa-eye"></i></a> </td>
                      @endif
                    <td data-title="Date & Time">{{ $list->approve_td }}</td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
            {{$lists->links()}}
          </div>
        </div>
      </div>
    </div>
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
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
{{-- <script src="/user/assets/js/add_rows.js"></script> --}}
<script src="/user/assets/js/fetch_bookrequest.js"></script>
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
<script src="/user/assets/js/map.js"></script>

</body>

</html>