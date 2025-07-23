<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Beasiswa Kampus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow rounded-4 border-0 w-100" style="max-width: 420px;">
        <div class="text-center mb-3">
            <h3 class="fw-bold mb-1">Beasiswa<span class="text-primary">Kampus</span></h3>
            <p class="text-muted">Formulir Pendaftaran</p>
        </div>

        <form id="registerForm">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Lengkap</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Aktif</label>
                <input type="email" id="email" name="email" class="form-control" required>
                <div id="emailError" class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="8">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required minlength="8">
            </div>

            <button type="submit" id="registerButton" class="btn btn-primary w-100">
                <span class="default-text">Daftar</span>
                <span class="spinner-text d-none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...
                </span>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");
    const button = document.getElementById("registerButton");
    const defaultText = button.querySelector(".default-text");
    const spinnerText = button.querySelector(".spinner-text");

    const emailInput = document.getElementById("email");
    const emailError = document.getElementById("emailError");

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        emailInput.classList.remove('is-invalid');
        emailError.textContent = "";

        // Ambil data form
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Ubah ke JSON
        const jsonData = JSON.stringify(data);

        // Tampilkan loading
        defaultText.classList.add("d-none");
        spinnerText.classList.remove("d-none");
        button.disabled = true;

        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: jsonData
            });

            const json = await response.json();

            if (response.ok) {
                localStorage.setItem("otp_user_id", json.data.id_user);
                window.location.href = "/otp"; // arahkan ke halaman OTP
            } else {
                // Tampilkan error jika ada
                if (json.errors?.email) {
                    emailInput.classList.add('is-invalid');
                    emailError.textContent = json.errors.email[0];
                }
                if (json.errors?.password) {
                    Swal.fire('Error', json.errors.password[0], 'error');
                }
                if (json.errors?.username) {
                    Swal.fire('Error', json.errors.username[0], 'error');
                }
                if (json.message) {
                    Swal.fire('Error', json.message, 'error');
                }
            }
        } catch (err) {
            Swal.fire('Error', 'Terjadi kesalahan saat mengirim data.', 'error');
            console.error(err);
        } finally {
            // Sembunyikan loading
            defaultText.classList.remove("d-none");
            spinnerText.classList.add("d-none");
            button.disabled = false;
        }
    });
});
</script>

</body>
</html>
