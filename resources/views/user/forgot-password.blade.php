<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Beasiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow rounded-4 border-0 bg-white p-4 w-100" style="max-width: 420px;">
            <div class="text-center mb-3">
                <h3 class="fw-bold mb-1">Beasiswa<span class="text-primary">Kampus</span></h3>
                <p class="text-muted">Reset Password</p>
            </div>

            <form id="forgotForm">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa fa-envelope"></i></span>
                        <input type="email" class="form-control rounded-end-pill" id="email" required>
                    </div>
                    <div class="invalid-feedback" id="emailError"></div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill" id="submitBtn">
                    <span id="btnText"><i class="fa fa-paper-plane me-1"></i> Kirim OTP</span>
                    <span id="btnLoading" class="d-none"><i class="fa fa-spinner fa-spin me-1"></i> Mengirim...</span>
                </button>
            </form>

            <div class="text-center mt-3">
                <small><a href="/login">Kembali ke login</a></small>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('forgotForm');
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Reset error
            emailInput.classList.remove('is-invalid');
            emailError.textContent = '';
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            try {
                const res = await fetch('/api/forgot-password/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: emailInput.value })
                });

                const data = await res.json();

                if (!res.ok) {
                    if (data.errors?.email) {
                        emailInput.classList.add('is-invalid');
                        emailError.textContent = data.errors.email[0];
                    }
                    throw new Error(data.message || 'Gagal mengirim OTP.');
                }

                // Simpan ID user untuk verifikasi OTP
                if (data.user?.id_user) {
                    localStorage.setItem('reset_user_id', data.user.id_user);
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                window.location.href = "/verifikasi-otp"; // ganti jika endpoint berbeda

            } catch (err) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message
                });
            } finally {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>
