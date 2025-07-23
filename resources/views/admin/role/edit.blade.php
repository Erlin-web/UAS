@extends('admin.layout')

@section('content')
<h4 class="fw-bold py-3 mb-4">Edit Role</h4>

<div class="card p-4">
    <form id="formEditRole">
        <div class="mb-3">
            <label for="id_role" class="form-label">ID Role</label>
            <input type="text" id="id_role" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label for="nama_role" class="form-label">Nama Role</label>
            <input type="text" id="nama_role" name="nama_role" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ambil token dari localStorage
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        // ambil id_role dari URL
        const pathParts = window.location.pathname.split('/');
        const id = pathParts[pathParts.length - 2];
        document.getElementById('id_role').value = id;

        const namaInput = document.getElementById('nama_role');

        // FETCH data role berdasarkan ID
        fetch(`/api/role/${id}`, {
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
            console.log('Data Role:', response);
            if (response.success && response.data) {
                namaInput.value = response.data.nama_role;
            } else {
                Swal.fire('Gagal', response.message || 'Data tidak ditemukan.', 'error');
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire('Error', error.message || 'Gagal memuat data role.', 'error');
        });

        // Handle submit UPDATE
        document.getElementById('formEditRole').addEventListener('submit', function (e) {
            e.preventDefault();

            const nama_role = namaInput.value.trim();

            if (!nama_role) {
                Swal.fire('Peringatan', 'Nama role tidak boleh kosong.', 'warning');
                return;
            }

            fetch(`/api/role/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nama_role: nama_role
                })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => {
                        throw new Error(err.message || 'Gagal memperbarui data.');
                    });
                }
                return res.json();
            })
            .then(response => {
                console.log('Update Response:', response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Role berhasil diperbarui!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('admin.role.index') }}";
                    });
                } else {
                    Swal.fire('Gagal', response.message || 'Gagal memperbarui data.', 'error');
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire('Error', error.message || 'Terjadi kesalahan saat memperbarui data.', 'error');
            });
        });
    });
</script>
@endsection
