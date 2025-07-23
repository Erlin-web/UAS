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
                    <h3 class="text-primary">Beasiswa Kampus</h3>
                </a>
                <div class="navbar-nav w-100">
                    <a href="" class="nav-item nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.role.index') }}" class="nav-item nav-link">
                        <i class="fa fa-user-shield me-2"></i> Role
                    </a>
                    <a href="{{ route('admin.user.index') }}" class="nav-item nav-link">
                        <i class="fa fa-user me-2"></i> User
                    </a>
                    <a href="{{ route('admin.beasiswa.index') }}" class="nav-item nav-link">
                        <i class="fa fa-graduation-cap me-2"></i> Beasiswa
                    </a>
                    <a href="{{ route('admin.pendaftaran.index') }}" class="nav-item nav-link">
                        <i class="fa fa-clipboard-list me-2"></i> Pendaftaran
                    </a>
                    <a href="{{ route('admin.persetujuan.index') }}" class="nav-item nav-link">
                        <i class='bx bx-check-shield me-2'></i> Persetujuan
                    </a>
                    <a href="{{ route('admin.verifikator.index') }}" class="nav-item nav-link">
                        <i class='bx bx-user-check me-2'></i> Verifikator
                    </a>
                    <a href="{{ route('admin.list_universitas.index') }}" class="nav-item nav-link">
                        <i class='bx bx-buildings me-2'></i> List Universitas
                    </a>

                    <!-- Dropdown Log -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class='bx bx-history me-2'></i> Log
                        </a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="{{ route('logs.logactivity') }}" class="dropdown-item">
                                <i class="fa fa-list-alt me-2"></i> Log Activity
                            </a>
                            <a href="{{ route('logs.logdatabase') }}" class="dropdown-item">
                                <i class="fa fa-database me-2"></i> Log Database
                            </a>
                            <a href="{{ route('logs.logerror') }}" class="dropdown-item">
                                <i class="fa fa-exclamation-triangle me-2 text-danger"></i> Log Error
                            </a>
                        </div>
                    </div>

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

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user me-2"></i> Akun
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <button class="dropdown-item d-flex align-items-center" id="btnLogout">
                                    <i class="fa fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- Navbar End -->

            {{-- Session success --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- Content --}}
            @yield('content')
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
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

    <!-- Logout Script -->
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
