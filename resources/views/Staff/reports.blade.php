@include('inc.staff-header');
  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="dashboard">
          <i class="bi bi-grid"></i>
          <span>Reports</span>
        </a>
      </li><!-- End Dashboard Nav -->

    @include('inc.staff-sidebar')
  </aside><!-- End Sidebar-->

  <main id="main" class="main">
    <div class="container">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <table class="table table-bordered" style="white-space: nowrap">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Book Number</th>
                  <th scope="col">Groups</th>
                  <th scope="col">Date & Time</th>
                </tr>
              </thead>
                <tbody id="check_point">
                  @foreach($approve_list as $list)
                  <tr>
                    <th scope="row">{{ $list->first_name }} {{ $list->last_name }}</th>
                    <td>{{ $list->book_number }}</td>
                    <td>{{ $list->groups }}</td>
                    <td>{{ $list->approve_td }}</td>
                    <td>
                    <a href="/staff/book/view/all?id={{ $list->id }}" class="btn btn-primary"><i class="far fa-eye"></i></a> 
                    <a href="/staff/book/delete?id={{ $list->id }}" class="btn btn-danger"><i class="fa fa-trash"></i></a> 
                    <a href="/staff/book/confirm?id={{ $list->id }}" class="btn btn-success"><i class="far fa-check-circle"></i></a> 
                    </td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
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
<script src="/user/assets/js/staff_send_notif.js"></script>

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