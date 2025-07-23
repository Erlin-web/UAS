@extends('admin.index')

@section('content')
<h4 class="fw-bold py-3 mb-4">Tambah Verifikator</h4>

    <div class="card">
        <div class="card-body">
            <form id="formVerifikator">
                @csrf

                <div class="mb-3">
                    <label for="id_user" class="form-label">Nama User</label>
                    <select class="form-select" id="id_user" name="id_user">
                        <option value="">-- Pilih User --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tahapan" class="form-label">Tahapan</label>
                    <input type="number" class="form-control" id="tahapan" name="tahapan" min="1" placeholder="Contoh: 1">
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.verifikator.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('auth_token');

            if (!token) {
                Swal.fire("Sesi Habis", "Silakan login kembali.", "warning")
                    .then(() => window.location.href = '/login');
                return;
            }

            // ✅ Ambil user dengan role 'verifikator'
            fetch('/api/user?role=verifikator', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                if (res.status === 401) {
                    Swal.fire("Sesi Habis", "Silakan login kembali.", "warning")
                        .then(() => window.location.href = '/login');
                    return;
                }

                const response = await res.json();
                const select = document.getElementById('id_user');
                select.innerHTML = '<option value="">-- Pilih User --</option>';

                if (response.success && Array.isArray(response.data)) {
                    if (response.data.length > 0) {
                        response.data.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id_user;
                            option.textContent = `${user.id_user} - ${user.username}`;
                            select.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "-- Tidak ada user verifikator --";
                        option.disabled = true;
                        option.selected = true;
                        select.appendChild(option);
                    }
                } else {
                    Swal.fire("Gagal", "Data user tidak valid atau kosong", "error");
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Gagal memuat data user", "error");
            });

            // ✅ Submit form verifikator
            document.getElementById('formVerifikator').addEventListener('submit', function (e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);

                fetch('/api/verifikator', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    if (res.status === 401) {
                        Swal.fire("Sesi Habis", "Silakan login kembali.", "warning")
                            .then(() => window.location.href = '/login');
                        return;
                    }

                    const response = await res.json();

                    if (res.ok && response.success) {
                        Swal.fire("Berhasil", "Verifikator berhasil ditambahkan", "success")
                            .then(() => {
                                window.location.href = '{{ route("admin.verifikator.index") }}';
                            });
                    } else {
                        Swal.fire("Gagal", response.message || "Gagal menyimpan data", "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Error", "Terjadi kesalahan saat menyimpan data", "error");
                });
            });
        });
    </script>



@endsection
