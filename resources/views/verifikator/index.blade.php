<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Projek UAS</title>

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="{{ asset('Template/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
  <link href="{{ asset('Template/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="{{ asset('Template/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Template Stylesheet -->
  <link href="{{ asset('Template/css/style.css') }}" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Boxicons CDN -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
  <div class="container-xxl position-relative bg-white d-flex p-0">
    <!-- Sidebar Start -->
    <div class="sidebar pe-4 pb-3">
      <nav class="navbar bg-light navbar-light">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
          <h3 class="text-primary">Verifikator</h3>
        </a>
        <div class="navbar-nav w-100">
          <a href="" class="nav-item nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt me-2"></i>Dashboard
          </a>
          <a href="{{ route('verifikator.pendaftaran.index') }}" class="nav-item nav-link">
            <i class="fa fa-clipboard-list me-2"></i>Pendaftaran
          </a>
        </div>
      </nav>
    </div>
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
      <!-- Navbar Start -->
      <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-2">
        <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
          <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
        </a>
        <a href="#" class="sidebar-toggler flex-shrink-0">
          <i class="fa fa-bars"></i>
        </a>
        <form class="d-none d-md-flex ms-4">
          <input class="form-control border-0" type="search" placeholder="Search">
        </form>

        <!-- Tombol logout di pojok kanan -->
        <div class="ms-auto">
          <button id="btnLogout" class="btn btn-outline-danger">
            <i class="fa fa-sign-out-alt me-2"></i> Logout
          </button>
        </div>
      </nav>
      <!-- Navbar End -->

      {{-- Cek apakah ada session 'success' --}}
      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif

      @yield('content')
    </div>
    <!-- Content End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
      <i class="bi bi-arrow-up"></i>
    </a>
  </div>

  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('Template/lib/chart/chart.min.js') }}"></script>
  <script src="{{ asset('Template/lib/easing/easing.min.js') }}"></script>
  <script src="{{ asset('Template/lib/waypoints/waypoints.min.js') }}"></script>
  <script src="{{ asset('Template/lib/owlcarousel/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('Template/lib/tempusdominus/js/moment.min.js') }}"></script>
  <script src="{{ asset('Template/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
  <script src="{{ asset('Template/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

  <!-- Template Javascript -->
  <script src="{{ asset('Template/js/main.js') }}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const btnLogout = document.getElementById('btnLogout');
      if (btnLogout) {
        btnLogout.addEventListener('click', (e) => {
          e.preventDefault();
          Swal.fire({
            title: 'Yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout'
          }).then((result) => {
            if (result.isConfirmed) {
              logoutUser();
            }
          });
        });
      }
    });

    function logoutUser() {
      const token = localStorage.getItem('auth_token');

      fetch('/api/logout', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      })
      .finally(() => {
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
      });
    }
  </script>
</body>
</html>
