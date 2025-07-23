<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password - Beasiswa</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f1f4ff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .card {
      background-color: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      max-width: 420px;
      width: 100%;
    }

    .card h3 {
      text-align: center;
      margin-bottom: 10px;
      color: #4e54c8;
    }

    .card p {
      text-align: center;
      color: #888;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: 500;
      margin-bottom: 6px;
      display: block;
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 12px;
      font-size: 14px;
    }

    .form-group .invalid-feedback {
      color: red;
      font-size: 13px;
      margin-top: 4px;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background-color: #4e54c8;
      color: white;
      border: none;
      border-radius: 30px;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #3c41a8;
    }

    .btn .fa {
      margin-right: 6px;
    }

    .text-center {
      text-align: center;
      margin-top: 20px;
    }

    .text-center a {
      color: #4e54c8;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="card">
    <h3>Beasiswa<span style="color: #ff7e5f;">Kampus</span></h3>
    <p>Masukkan password baru Anda</p>

    <form id="resetForm">
      <div class="form-group">
        <label>Password Baru</label>
        <input type="password" id="password" required minlength="8" />
        <div class="invalid-feedback" id="passwordError"></div>
      </div>

      <div class="form-group">
        <label>Konfirmasi Password</label>
        <input type="password" id="confirmPassword" required minlength="8" />
        <div class="invalid-feedback" id="confirmError"></div>
      </div>

      <button type="submit" class="btn" id="submitBtn">
        <span id="btnText"><i class="fa fa-sync-alt"></i> Reset Password</span>
        <span id="btnLoading" class="d-none"><i class="fa fa-spinner fa-spin"></i> Memproses...</span>
      </button>
    </form>

    <div class="text-center">
      <a href="/login">Kembali ke login</a>
    </div>
  </div>

  <script>
    const form = document.getElementById('resetForm');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirmPassword');
    const passwordError = document.getElementById('passwordError');
    const confirmError = document.getElementById('confirmError');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', async function(e) {
      e.preventDefault();

      passwordError.textContent = '';
      confirmError.textContent = '';
      passwordInput.classList.remove('is-invalid');
      confirmInput.classList.remove('is-invalid');

      if (passwordInput.value !== confirmInput.value) {
        confirmInput.classList.add('is-invalid');
        confirmError.textContent = 'Konfirmasi password tidak cocok.';
        return;
      }

      btnText.classList.add('d-none');
      btnLoading.classList.remove('d-none');
      submitBtn.disabled = true;

      const id_user = localStorage.getItem('reset_user_id');

      if (!id_user) {
        await Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'ID pengguna tidak ditemukan. Silakan ulangi proses.'
        });
        return;
      }

      try {
        const res = await fetch('/api/forgot-password/reset', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            id_user: id_user,
            password: passwordInput.value,
            password_confirmation: confirmInput.value
          })
        });

        const data = await res.json();

        if (!res.ok) throw new Error(data.message || 'Reset password gagal.');

        await Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: data.message,
          timer: 2000,
          showConfirmButton: false
        });

        localStorage.removeItem('reset_user_id');
        window.location.href = '/login';

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
