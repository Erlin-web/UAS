@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Persetujuan</h4>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0">Daftar Persetujuan</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 15%;">ID Pendaftaran</th>
                        <th>Nama User</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-persetujuan">
                    <tr>
                        <td colspan="6" class="text-center text-muted align-middle py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <br>
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            loadPersetujuan(token);
        });

        function loadPersetujuan(token) {
            fetch('/api/persetujuan', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal mengambil data');
                    return res.json();
                })
                .then(response => {
                    const tbody = document.getElementById("tabel-persetujuan");
                    tbody.innerHTML = "";

                    const data = response.data || [];

                    if (data.length === 0) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-muted">Data persetujuan belum tersedia.</td>
                        </tr>`;
                        return;
                    }

                    data.forEach((item, index) => {
                        tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.pendaftaran?.id_pendaftaran || '-'}</td>
                            <td>${item.user?.username || '-'}</td>
                            <td>${item.status}</td>
                            <td>${item.catatan}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Lihat" onclick="showPersetujuan(${item.id_persetujuan})">
                                    <i class="bx bx-show"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                })
                .catch(error => {
                    console.error("Gagal memuat data persetujuan:", error);
                    document.getElementById("tabel-persetujuan").innerHTML = `
                    <tr><td colspan="6" class="text-center text-danger">Gagal memuat data persetujuan.</td></tr>`;
                });
        }

        function showPersetujuan(id) {
            const token = localStorage.getItem('auth_token');
            fetch(`/api/persetujuan/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(response => {
                    const data = response.data;
                    Swal.fire({
                        title: 'Detail Persetujuan',
                        html: `
                        <div style="text-align:left">
                            <p><strong>ID:</strong> ${data.id_persetujuan}</p>
                            <p><strong>Nama User:</strong> ${data.user?.username || '-'}</p>
                            <p><strong>ID Pendaftaran:</strong> ${data.pendaftaran?.id_pendaftaran || '-'}</p>
                            <p><strong>Status:</strong> ${data.status}</p>
                            <p><strong>Catatan:</strong> ${data.catatan}</p>
                        </div>
                    `,
                        confirmButtonText: 'Tutup'
                    });
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Gagal mengambil data detail.', 'error');
                });
        }
    </script>
@endsection
