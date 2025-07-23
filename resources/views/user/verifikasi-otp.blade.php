<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS (Opsional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (untuk ikon) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow rounded-4 border-0 bg-white p-4 w-100" style="max-width: 420px;">
            <div class="text-center mb-3">
                <h3 class="fw-bold mb-1">Beasiswa<span class="text-primary">Kampus</span></h3>
                <p class="text-muted">Masukkan 6 digit OTP yang dikirim ke email Anda.</p>
            </div>

            <form id="otpForm">
                <div class="mb-3 text-center d-flex justify-content-between gap-2 justify-content-center">
                    <input type="text" class="form-control text-center otp-input" maxlength="1" inputmode="numeric" pattern="\d*" style="width: 45px; height: 55px; font-size: 24px;" autocomplete="off" required>
                    <input type="text" class="form-control text-center otp-input" maxlength="1" inputmode="numeric" pattern="\d*" style="width: 45px; height: 55px; font-size: 24px;" autocomplete="off" required>
                    <input type="text" class="form-control text-center otp-input" maxlength="1" inputmode="numeric" pattern="\d*" style="width: 45px; height: 55px; font-size: 24px;" autocomplete="off" required>
                    <input type="text" class="form-control text-center otp-input" maxlength="1" inputmode="numeric" pattern="\d*" style="width: 45px; height: 55px; font-size: 24px;" autocomplete="off" required>
                    <input type="text" class="form-control text-center otp-input" maxlength="1" inputmode="numeric" pattern="\d*" style="width: 45px; height: 55px; font-size: 24px;" autocomplete="off" required>
                    <input type="text" class="form-control text-center otp-input" maxlength="1" inputmode="numeric" pattern="\d*" style="width: 45px; height: 55px; font-size: 24px;" autocomplete="off" required>
                </div>

                <button type="submit" id="submitOtpBtn" class="btn btn-primary w-100 rounded-pill">
                    <span class="default-text"><i class="fa fa-check-circle me-1"></i> Verifikasi</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">Belum menerima OTP?
                    <a href="#" id="resendOtp" class="text-primary text-decoration-none">Kirim ulang</a>
                    <span id="resendSpinner" class="spinner-border spinner-border-sm text-primary d-none"></span>
                </small>
            </div>

            <div class="mt-3 text-center">
                <span id="otpMessage" class="fw-semibold"></span>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const otpForm = document.getElementById("otpForm");
        const otpInputs = document.querySelectorAll(".otp-input");
        const otpMessage = document.getElementById("otpMessage");
        const submitBtn = document.getElementById("submitOtpBtn");
        const spinner = submitBtn.querySelector(".spinner-border");
        const resendOtp = document.getElementById("resendOtp");
        const resendSpinner = document.getElementById("resendSpinner");

        otpInputs.forEach((input, i) => {
            input.addEventListener("input", () => {
                if (input.value.length === 1 && i < otpInputs.length - 1) {
                    otpInputs[i + 1].focus();
                }
            });
        });

        function toggleButtonLoading(button, spinner, isLoading) {
            button.disabled = isLoading;
            spinner.classList.toggle('d-none', !isLoading);
            button.querySelector(".default-text").classList.toggle('d-none', isLoading);
        }

        otpForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            const otp = Array.from(otpInputs).map(input => input.value).join('');
            if (otp.length !== 6) {
                otpMessage.textContent = "OTP tidak lengkap atau tidak valid.";
                otpMessage.style.color = 'red';
                return;
            }

            toggleButtonLoading(submitBtn, spinner, true);
            otpMessage.textContent = "";

            try {
                const response = await fetch('/api/forgot-password/verifikasi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ otp })
                });

                const json = await response.json();

                if (json.success) {
                    otpMessage.textContent = json.message;
                    otpMessage.style.color = 'green';
                    localStorage.setItem("reset_user_id", json.data.id_user);
                    setTimeout(() => window.location.href = '/reset-password', 1000);
                } else {
                    otpMessage.textContent = json.message || "OTP tidak valid.";
                    otpMessage.style.color = 'red';
                }
            } catch (error) {
                otpMessage.textContent = "Terjadi kesalahan saat verifikasi.";
                otpMessage.style.color = 'red';
            } finally {
                toggleButtonLoading(submitBtn, spinner, false);
            }
        });

        resendOtp.addEventListener("click", async function(e) {
            e.preventDefault();

            const id_user = localStorage.getItem("reset_user_id");
            if (!id_user) {
                otpMessage.textContent = "ID tidak ditemukan.";
                otpMessage.style.color = 'red';
                return;
            }

            resendOtp.classList.add("disabled");
            resendSpinner.classList.remove("d-none");

            try {
                const user = await fetch(`/api/user/${id_user}`).then(res => res.json());

                if (user?.email) {
                    const response = await fetch('/api/forgot-password/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: user.email })
                    });

                    const json = await response.json();
                    otpMessage.textContent = json.message;
                    otpMessage.style.color = response.ok ? 'green' : 'red';
                } else {
                    otpMessage.textContent = "Email tidak ditemukan.";
                    otpMessage.style.color = 'red';
                }
            } catch (error) {
                otpMessage.textContent = "Gagal mengirim ulang OTP.";
                otpMessage.style.color = 'red';
            } finally {
                resendOtp.classList.remove("disabled");
                resendSpinner.classList.add("d-none");
            }
        });
    </script>
</body>
</html>
