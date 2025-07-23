@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Data Role</h4>

    <div class="card border-start border-primary border-4 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-semibold">Daftar Role</h5>
            <a href="{{ route('admin.role.create') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus"></i> Tambah Role
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th>Nama Role</th>
                        <th class="text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-role">
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
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
            if (!token) {
                window.location.href = '/login';
                return;
            }

            fetch('/api/role', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => {
                            throw new Error(err.message || 'Gagal memuat data role.');
                        });
                    }
                    return res.json();
                })
                .then(response => {
                    console.log('API Response:', response);

                    // Menyesuaikan struktur JSON dari API
                    const roles = response.data || response.roles || [];

                    const tbody = document.getElementById('tabel-role');
                    tbody.innerHTML = '';

                    if (!roles || roles.length === 0) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center text-muted">Data role kosong</td>
                        </tr>
                    `;
                        return;
                    }

                    roles.forEach((role, index) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${role.nama_role}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-warning" title="Edit"
                                onclick="window.location.href='/admin/role/${role.id_role}/edit'">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                onclick="confirmDeleteRole(${role.id_role})">
                                <i class='bx bx-trash'></i>
                            </button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('tabel-role').innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center text-danger">${error.message}</td>
                    </tr>
                `;
                });
        });

        function confirmDeleteRole(id_role) {
            const token = localStorage.getItem('api_token');
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
                    fetch(`/api/role/${id_role}`, {
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
