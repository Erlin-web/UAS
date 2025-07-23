@extends('admin.layout')

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
                                onclick="showPendaftaran(${item.id_pendaftaran})">
                                <i class='bx bx-show'></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" title="Edit"
                                onclick="window.location.href='/admin/pendaftaran/${item.id_pendaftaran}/edit'">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"
                                onclick="hapusPendaftaran(${item.id_pendaftaran})">
                                <i class='bx bx-trash'></i>
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

        function showPendaftaran(id_pendaftaran) {
            const token = localStorage.getItem('auth_token');
            console.log("Token on showPendaftaran:", token);

            if (!token) {
                window.location.href = '/login';
                return;
            }

            fetch(`/api/pendaftaran/${id_pendaftaran}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    console.log("Status showPendaftaran:", res.status);
                    if (res.status === 401) {
                        window.location.href = '/login';
                        return;
                    }
                    if (!res.ok) {
                        throw new Error('Gagal mengambil detail data.');
                    }
                    return res.json();
                })
                .then(response => {
                    if (!response) return;

                    const pendaftaran = response.data;

                    let dokumenHtml = '-';
                    if (pendaftaran.dokumen && pendaftaran.dokumen.length > 0) {
                        dokumenHtml = '';

                        pendaftaran.dokumen.forEach(dok => {
                            const ext = dok.nama_file.split('.').pop().toLowerCase();
                            const path = `/storage/${dok.nama_file}`;

                            if (['jpg', 'jpeg', 'png'].includes(ext)) {
                                dokumenHtml += `
                                <div style="margin-bottom: 15px;">
                                    <img src="${path}" alt="Dokumen Gambar" style="max-width: 100%; max-height: 300px;" />
                                </div>
                            `;
                            }
                        });
                    }


                    Swal.fire({
                        title: 'Detail Pendaftaran',
                        width: 800,
                        html: `
                        <div style="text-align: left">
                            <p><strong>No:</strong> ${pendaftaran.id_pendaftaran}</p>
                            <p><strong>Nama User:</strong> ${pendaftaran.user?.username || '-'}</p>
                            <p><strong>Nama Beasiswa:</strong> ${pendaftaran.beasiswa?.nama_beasiswa || '-'}</p>
                            <p><strong>Nama Universitas:</strong> ${pendaftaran.list_universitas?.nama_universitas || '-'}</p>
                            <p><strong>Telp:</strong> ${pendaftaran.telp || '-'}</p>
                            <p><strong>Alamat:</strong> ${pendaftaran.alamat || '-'}</p>
                            <p><strong>Dokumen:</strong></p>
                            ${dokumenHtml}
                        </div>
                    `,
                        confirmButtonText: 'Tutup'
                    });
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Gagal mengambil data pendaftaran.', 'error');
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
