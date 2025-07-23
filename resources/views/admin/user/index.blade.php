@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Data User</h4>

    <div class="card border-start border-primary border-4 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-semibold">Daftar User</h5>
            <a href="{{ route('admin.user.create') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus"></i> Tambah User
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Username</th>
                        <th style="width: 30%;">Email</th>
                        <th style="width: 15%;">Role</th>
                        <th class="text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-user" class="table-border-bottom-0">
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        #tabel-user td {
            max-width: 250px;
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            fetch('/api/user', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Gagal memuat data user.');
                    }
                    return res.json();
                })
                .then(response => {
                    const users = response.data;
                    const tbody = document.getElementById('tabel-user');
                    tbody.innerHTML = '';

                    if (!users || users.length === 0) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-muted">Data user kosong</td>
                        </tr>
                    `;
                        return;
                    }

                    users.forEach((user, index) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${user.username || '-'}</td>
                        <td>${user.email || '-'}</td>
                        <td>${user.role?.nama_role || '-'}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-warning me-1" title="Edit"
                                onclick="window.location.href='/admin/user/${user.id_user}/edit'">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                onclick="confirmDeleteUser(${user.id_user})">
                                <i class='bx bx-trash'></i>
                            </button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('tabel-user').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">Gagal memuat data user</td>
                    </tr>
                `;
                });
        });

        function confirmDeleteUser(id_user) {
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
                    fetch(`/api/user/${id_user}`, {
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
