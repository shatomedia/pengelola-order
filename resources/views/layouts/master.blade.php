<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png"/>
  <link rel="icon" type="image/png" href="../assets/img/favicon.png"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/2.2.0/css/scroller.bootstrap5.min.css" />
  
  <title>
    Shatomedia | {{ $title }}
  </title>
  @include('layouts.head')
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
 
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
  @include('sweetalert::alert')
  @include('layouts.sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('layouts.navbar')
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      @if(session()->has('success'))
      <div class="row">
        <div class="col-12">
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
        </div>
      </div>
      @endif
      
      @yield('content')
      
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                ©
                <script>
                  document.write(new Date().getFullYear())
                </script> |
                Shatomedia
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="{{ asset('js/core/popper.min.js') }}"></script>
  <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fff",
          data:  [130, 234, 125, 450, 200, 100, 220, 500, 100, 400, 230, 500],
          maxBarThickness: 6
        },],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 15,
              font: {
                size: 14,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              display: false
            },
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
          label: "Shopee",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#cb0c9f",
          borderWidth: 3,
          backgroundColor: gradientStroke1,
          fill: true,
          data: [50, 40, 30, 22, 50, 25, 40, 23, 50, 34, 23, 40],
          maxBarThickness: 6

        },
        {
          label: "Reseller",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#3A416F",
          borderWidth: 3,
          backgroundColor: gradientStroke2,
          fill: true,
          data: [30, 90, 40, 14, 29, 29, 34, 23, 40, 34, 23, 40],
          maxBarThickness: 6
        },
        {
          label: "Tokopedia",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#3A416F",
          borderWidth: 3,
          backgroundColor: gradientStroke2,
          fill: true,
          data: [30, 90, 40, 140, 29, 29, 34, 23, 40, 34, 23, 40],
          maxBarThickness: 6
        },
        {
          label: "Lazada",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#3A416F",
          borderWidth: 3,
          backgroundColor: gradientStroke2,
          fill: true,
          data: [30, 90, 40, 14, 29, 290, 34, 23, 40, 34, 23, 40],
          maxBarThickness: 6
        },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js" integrity="sha512-he8U4ic6kf3kustvJfiERUpojM8barHoz0WYpAUDWQVn61efpm3aVAD8RWL8OloaDDzMZ1gZiubF9OSdYBqHfQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>
<script>
  $(function() {

    //Date range picker
      const dateRangePicker = $('.daterange');
      dateRangePicker.daterangepicker({
          autoUpdateInput: false,
          locale: 'id',
      });

      dateRangePicker.on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
      });

      dateRangePicker.on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
      });
    // $('.daterange').daterangepicker({
    //     locale: 'id',
    //     format: 'YYYY-MM-DD',
    //     showClose: true,
    //     showClear: true
    // }, function(start, end, label) {
    //   console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    //   });
  });
  </script>


  
  <script>
    $(function () {
      new DataTable('#dataTables', {
        // deferRender: true,
        scrollCollapse: true,
        scrollX: true,
      });
    });
    
  </script>


</body>

</html>