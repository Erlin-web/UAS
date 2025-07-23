@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Data Beasiswa</h4>

    <div class="card border-start border-primary border-4 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-semibold">Data Jenis Beasiswa</h5>
            <a href="{{ route('admin.beasiswa.create') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus"></i> Tambah Data
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 20%;">Nama Beasiswa</th>
                        <th style="width: 35%;">Deskripsi</th>
                        <th style="width: 30%;">Persyaratan</th>
                        <th class="text-center" style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-beasiswa">
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <div class="spinner-border text-primary" role="status"></div>
                            <br>Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <style>
        /* Membatasi panjang isi sel agar tidak meluber ke samping */
        #tabel-beasiswa td {
            max-width: 250px;
            white-space: normal;
            word-wrap: break-word;
            word-break: break-all;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            fetch('/api/beasiswa', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Gagal memuat data beasiswa.');
                    }
                    return res.json();
                })
                .then(response => {
                    const beasiswas = response.data;
                    const tbody = document.getElementById('tabel-beasiswa');
                    tbody.innerHTML = '';

                    if (!beasiswas || beasiswas.length === 0) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-muted">Data beasiswa kosong</td>
                        </tr>
                    `;
                        return;
                    }

                    beasiswas.forEach((item, index) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${item.nama_beasiswa}</td>
                        <td>${item.deskripsi}</td>
                        <td>${item.persyaratan}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-warning me-1" title="Edit"
                                onclick="window.location.href='/admin/beasiswa/${item.id_beasiswa}/edit'">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                onclick="confirmDeleteBeasiswa(${item.id_beasiswa})">
                                <i class='bx bx-trash'></i>
                            </button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('tabel-beasiswa').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">Gagal memuat data beasiswa</td>
                    </tr>
                `;
                });
        });

        function confirmDeleteBeasiswa(id_beasiswa) {
            const token = localStorage.getItem('auth_token');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/beasiswa/${id_beasiswa}`, {
                            method: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => {
                            if (!res.ok) {
                                return res.json().then(err => {
                                    throw new Error(err.message || 'Gagal menghapus data.');
                                });
                            }
                            return res.json();
                        })
                        .then(response => {
                            Swal.fire('Berhasil!', response.message, 'success')
                                .then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire('Gagal', error.message, 'error');
                        });
                }
            });
        }
    </script>
@endsection
