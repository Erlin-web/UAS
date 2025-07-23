@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Tambah User</h4>

    <div class="card">
        <div class="card-body">
            <form id="formCreateUser">
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" id="id_role" required>
                        <option value="">Pilih Role</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');

            // Load role
            fetch('/api/role', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('id_role');
                data.data.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role.id_role;
                    option.textContent = role.nama_role;
                    select.appendChild(option);
                });
            });

            document.getElementById('formCreateUser').addEventListener('submit', async (e) => {
                e.preventDefault();

                const payload = {
                    id_role: document.getElementById('id_role').value,
                    username: document.getElementById('username').value.trim(),
                    email: document.getElementById('email').value.trim(),
                    password: document.getElementById('password').value.trim(),
                };

                try {
                    const res = await fetch('/api/user', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await res.json();

                    if (!res.ok) {
                        throw new Error(result.message || 'Gagal menambah user');
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message
                    }).then(() => {
                            window.location.href = "{{ route('admin.user.index') }}";
                        });

                    // Kosongkan form
                    document.getElementById('formCreateUser').reset();

                    // Panggil refresh tabel di index
                    if (window.refreshUserTable) {
                        window.refreshUserTable();
                    }

                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                }
            });
        });

    </script>
@endsection
