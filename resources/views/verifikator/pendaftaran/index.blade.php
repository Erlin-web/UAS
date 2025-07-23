@extends('verifikator.index')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Pendaftaran</h4>

    <div class="card border-start border-primary border-4 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-semibold">Daftar Pendaftaran</h5>
            <a href="{{ route('admin.pendaftaran.create') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus"></i> Tambah Data
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th>Nama User</th>
                        <th>Nama Beasiswa</th>
                        <th>Nama Universitas</th>
                        <th>Telp</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-pendaftaran">
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <div class="spinner-border text-primary" role="status"></div>
                            <br>Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');
            console.log("Token on DOMContentLoaded:", token);

            if (!token) {
                console.warn("Token tidak ditemukan. Arahkan ke login.");
                window.location.href = '/login';
                return;
            }

            loadPendaftaran(token);
        });

        function loadPendaftaran(token) {
            fetch('/api/pendaftaran', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    console.log("Status loadPendaftaran:", res.status);
                    if (res.status === 401) {
                        window.location.href = '/login';
                        return;
                    }
                    if (!res.ok) {
                        throw new Error('Gagal memuat data pendaftaran.');
                    }
                    return res.json();
                })
                .then(response => {
                    if (!response) return;

                    const tbody = document.getElementById("tabel-pendaftaran");
                    tbody.innerHTML = "";

                    const data = response.data;

                    if (!data || data.length === 0) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center text-muted">Data pendaftaran belum tersedia.</td>
                        </tr>
                    `;
                        return;
                    }

                    data.forEach((item, index) => {
                        const tr = document.createElement('tr');

                        tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${item.user?.username || '-'}</td>
                        <td>${item.beasiswa?.nama_beasiswa || '-'}</td>
                        <td>${item.list_universitas?.nama_universitas|| '-'}</td>
                        <td>${item.telp || '-'}</td>
                        <td>${item.alamat || '-'}</td>
                        <td>${item.status || '-'}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="Lihat"
                                onclick="window.location.href='/verifikator/pendaftaran/${item.id_pendaftaran}/show'">
                                <i class='bx bx-show'></i>
                            </button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('tabel-pendaftaran').innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-danger">
                            Gagal memuat data pendaftaran.
                        </td>
                    </tr>
                `;
                });
        }

        function hapusPendaftaran(id_pendaftaran) {
            const token = localStorage.getItem('auth_token');
            console.log("Token on hapusPendaftaran:", token);

            if (!token) {
                window.location.href = '/login';
                return;
            }

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data Pendaftaran akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/pendaftaran/${id_pendaftaran}`, {
                            method: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => {
                            console.log("Status hapusPendaftaran:", res.status);
                            if (res.status === 401) {
                                window.location.href = '/login';
                                return;
                            }
                            if (!res.ok) {
                                return res.json().then(err => {
                                    throw new Error(err.message || 'Gagal menghapus data.');
                                });
                            }
                            return res.json();
                        })
                        .then(response => {
                            if (!response) return;

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadPendaftaran(token);
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Gagal', error.message, 'error');
                        });
                }
            });
        }
    </script>
@endsection
