@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Data Universitas</h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Universitas</h5>
            <a href="{{ route('admin.list_universitas.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Universitas
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Universitas</th>
                        <th>Alamat Universitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-list_universitas">
                    <tr>
                        <td colspan="5" class="text-center text-muted">Memuat data...</td>
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

            loadListUniversitas(token);
        });

        async function loadListUniversitas(token) {
            const tbody = document.getElementById("tabel-list_universitas");
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Memuat data...</td></tr>`;

            try {
                const res = await fetch('/api/list_universitas', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const response = await res.json();

                tbody.innerHTML = "";

                if (response.success && response.data.length > 0) {
                    response.data.forEach((item, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.kode}</td>
                                <td>${item.nama_universitas}</td>
                                <td>${item.alamat_universitas}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning" title="Edit"
                                        onclick="window.location.href='/admin/list_universitas/${item.kode}/edit'">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"
                                        onclick="hapusListUniversitas('${item.kode}')">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-muted">Data universitas belum tersedia.</td>
                        </tr>
                    `;
                }
            } catch (error) {
                console.error("Gagal mengambil data universitas:", error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">Gagal memuat data universitas.</td>
                    </tr>
                `;
            }
        }

        function hapusListUniversitas(NPSN) {
            const token = localStorage.getItem('api_token');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data universitas akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch(`/api/list_universitas/${NPSN}`, {
                            method: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            throw new Error(data.message || 'Gagal menghapus data.');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        loadListUniversitas(token);
                    } catch (error) {
                        console.error('Gagal menghapus universitas:', error);
                        Swal.fire('Terjadi kesalahan', error.message, 'error');
                    }
                }
            });
        }
    </script>
@endsection
