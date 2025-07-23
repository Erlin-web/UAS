@extends('admin.layout')

@section('content')

<h4 class="fw-bold py-3 mb-4">Edit User</h4>

<div class="card p-4">
    <form id="formEditUser">
        <input type="hidden" id="id_user">

        <div class="mb-3">
            <label for="id_role" class="form-label">Role</label>
            <select id="id_role" name="id_role" class="form-select" required>
                <option value="">-- Pilih Role --</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (isi jika ingin ganti)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');

            if (!token) {
                window.location.href = '/login';
                return;
            }

            const pathParts = window.location.pathname.split('/');
            const id_user = pathParts[pathParts.length - 2];
            document.getElementById('id_user').value = id_user;

            loadRole();
            loadUser();

            function loadRole() {
                fetch('/api/role', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
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
                    const select = document.getElementById('id_role');
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(role => {
                            const option = document.createElement('option');
                            option.value = role.id_role;
                            option.text = role.nama_role;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', error.message || 'Gagal memuat data role.', 'error');
                });
            }

            function loadUser() {
                fetch(`/api/user/${id_user}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => {
                            throw new Error(err.message || 'Gagal memuat data user.');
                        });
                    }
                    return res.json();
                })
                .then(response => {
                    if (response.success && response.data) {
                        document.getElementById('id_role').value = response.data.id_role;
                        document.getElementById('username').value = response.data.username;
                        document.getElementById('email').value = response.data.email;
                    } else {
                        Swal.fire('Gagal', response.message || 'Data user tidak ditemukan.', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', error.message || 'Gagal memuat data user.', 'error');
                });
            }

            const form = document.getElementById('formEditUser');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const data = {
                    id_role: document.getElementById('id_role').value,
                    username: document.getElementById('username').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value
                };

                fetch(`/api/user/${id_user}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => {
                            console.error('API ERROR:', err);
                            throw new Error(err.message || 'Gagal update user.');
                        });
                    }
                    return res.json();
                })
                .then(response => {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'User berhasil diperbarui!',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('admin.user.index') }}";
                        });
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal memperbarui user.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', err.message || 'Terjadi kesalahan pada server.', 'error');
                });
            });
        });
    </script>


@endsection
