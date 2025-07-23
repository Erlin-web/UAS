@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Tambah Role</h4>

    <div class="card">
        <div class="card-body">
            <form id="formCreateRole">
                <div class="mb-3">
                    <label for="nama_role" class="form-label">Nama Role</label>
                    <input type="text" class="form-control" id="nama_role" name="nama_role" placeholder="Masukkan nama role" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('formCreateRole').addEventListener('submit', async function(e) {
            e.preventDefault();

            const nama_role = document.getElementById('nama_role').value;
            const token = localStorage.getItem('auth_token');

            try {
                const response = await fetch('/api/role', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ nama_role })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal menyimpan data.');
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message
                }).then(() => {
                    window.location.href = "{{ route('admin.role.index') }}";
                });

            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            }
        });
    </script>
@endsection
