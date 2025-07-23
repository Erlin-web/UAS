@extends('verifikator.index')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pendaftaran /</span> Verifikasi Pendaftaran
        </h4>

        <div class="card shadow">
            <div class="card-body">
                <!-- Nama User -->
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-person-circle me-1"></i> Nama Pengguna</label>
                    <div class="form-control bg-light" id="nama_user">-</div>
                </div>

                <!-- Nama Beasiswa -->
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-award me-1"></i> Beasiswa</label>
                    <div class="form-control bg-light" id="nama_beasiswa">-</div>
                </div>

                <!-- Universitas -->
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-building me-1"></i> Universitas</label>
                    <div class="form-control bg-light" id="nama_universitas">-</div>
                </div>

                <!-- Telepon -->
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-telephone me-1"></i> Nomor Telepon</label>
                    <div class="form-control bg-light" id="telp">-</div>
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-geo-alt me-1"></i> Alamat</label>
                    <div class="form-control bg-light" id="alamat">-</div>
                </div>

                <!-- Dokumen -->
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-paperclip me-1"></i> Dokumen</label>
                    <div class="form-control bg-light" id="dokumen_list">Memuat dokumen...</div>
                </div>

                <!-- Catatan -->
                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="bi bi-pencil-square me-1"></i> Catatan
                        Verifikator</label>
                    <textarea class="form-control" id="catatan" rows="3" placeholder="Tulis catatan jika perlu..."></textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="text-end">
                    <button class="btn btn-success me-2" onclick="kirimApprovail('Setujui')">
                        <i class="bi bi-check-circle me-1"></i> Setujui
                    </button>
                    <button class="btn btn-danger me-2" onclick="kirimApprovail('Tolak')">
                        <i class="bi bi-x-circle me-1"></i> Tolak
                    </button>
                    <a href="{{ route('verifikator.pendaftaran.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                Swal.fire('Token Hilang', 'Silakan login ulang.', 'error');
                return;
            }

            // Ambil ID dari URL (/verifikator/pendaftaran/{id}/show)
            const match = window.location.pathname.match(/\/pendaftaran\/(\d+)/);
            const id = match ? match[1] : null;

            if (!id) {
                Swal.fire('Gagal', 'ID pendaftaran tidak valid di URL.', 'error');
                return;
            }

            try {
                const res = await fetch(`/api/pendaftaran/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.message || 'Gagal memuat data pendaftaran.');
                }

                const {
                    data
                } = await res.json();

                document.getElementById('nama_user').textContent = data.user?.username ?? '-';
                document.getElementById('nama_beasiswa').textContent = data.beasiswa?.nama_beasiswa ?? '-';
                document.getElementById('nama_universitas').textContent = data.list_universitas
                    ?.nama_universitas ?? '-';
                document.getElementById('telp').textContent = data.telp ?? '-';
                document.getElementById('alamat').textContent = data.alamat ?? '-';

                const dokumenList = document.getElementById('dokumen_list');
                if (data.dokumen && data.dokumen.length > 0) {
                    dokumenList.innerHTML = data.dokumen.map(d => `
                    <a href="/storage/${d.nama_file}" target="_blank">
                        ${d.nama || d.nama_file.split('/').pop()}
                    </a>
                `).join('<br>');
                } else {
                    dokumenList.innerHTML = '<em>Tidak ada dokumen</em>';
                }

            } catch (error) {
                console.error(error);
                Swal.fire('Error', error.message, 'error');
            }
        });

        async function kirimApprovail(status) {
            const token = localStorage.getItem('auth_token');
            const match = window.location.pathname.match(/\/pendaftaran\/(\d+)/);
            const id = match ? match[1] : null;
            const catatan = document.getElementById('catatan').value;

            if (!token) {
                Swal.fire('Error', 'Token tidak ditemukan. Silakan login ulang.', 'error');
                return;
            }

            if (!id) {
                Swal.fire('Error', 'ID pendaftaran tidak valid.', 'error');
                return;
            }

            try {
                const res = await fetch(`/api/verifikator/pendaftaran/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: status,
                        catatan: catatan
                    })
                });

                const response = await res.json();

                if (res.ok && response.success) {
                    Swal.fire('Sukses', response.message, 'success').then(() => {
                        window.location.href = '/verifikator/pendaftaran';
                    });
                } else {
                    Swal.fire('Gagal', response.message || 'Gagal menyimpan keputusan.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan saat mengirim data.', 'error');
            }
        }
    </script>
@endsection
