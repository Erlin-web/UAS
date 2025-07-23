<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&display=swap" rel="stylesheet" />

    <!-- Icon Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Bootstrap & Template CSS -->
    <link href="{{ asset('Template/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Template/css/style.css') }}" rel="stylesheet" />

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Login Form -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <h3>Login</h3>
                        </div>

                        <form id="loginForm">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" placeholder="name@example.com" required />
                                <label for="email">Email address</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" placeholder="Password" required />
                                <label for="password">Password</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" />
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="{{ route('forgot-password') }}">Forgot Password?</a>
                            </div>
                            <button id="loginButton" type="submit" class="btn btn-primary py-3 w-100 mb-4">
                                <span class="default-text">Sign In</span>
                                <span class="spinner-text d-none"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</span>
                            </button>
                            <p class="text-center mb-0">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Login Form -->
    </div>

    <!-- JS Libraries -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> --}}

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const loginBtn = document.getElementById('loginButton');
            const defaultText = loginBtn.querySelector('.default-text');
            const spinnerText = loginBtn.querySelector('.spinner-text');

            loginBtn.disabled = true;
            defaultText.classList.add('d-none');
            spinnerText.classList.remove('d-none');

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();
                console.log("Login result:", result); // <-- Tambahkan debug ini

                if (!response.ok) {
                    throw new Error(result.message || 'Login gagal.');
                }

                const token = result.data.token;
                const user = result.data.user;
                const role = user.role?.nama_role?.toLowerCase(); // paksa lowercase
                const username = user.nama || user.username || 'Pengguna';

                console.log("Detected role:", role); // <-- Debug role

                // Simpan ke localStorage
                localStorage.setItem('auth_token', token);
                localStorage.setItem('user_role', role);
                localStorage.setItem('user_username', username);

                await Swal.fire({
                    icon: 'success',
                    title: 'Selamat Datang!',
                    text: 'Halo, ' + username,
                    timer: 1500,
                    showConfirmButton: false
                });

                // Redirect berdasarkan role
                const redirectMap = {
                    admin: '/admin/dashboard',
                    verifikator: '/verifikator/dashboard',
                    peserta: '/peserta/dashboard'
                };

                if (!role || !redirectMap[role]) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Gagal Redirect',
                        text: 'Role pengguna tidak dikenali.'
                    });
                    return;
                }

                window.location.href = redirectMap[role];

            } catch (error) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: error.message
                });
            } finally {
                loginBtn.disabled = false;
                defaultText.classList.remove('d-none');
                spinnerText.classList.add('d-none');
            }
        });
    </script>


</body>
</html>
