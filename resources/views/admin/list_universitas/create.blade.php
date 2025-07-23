@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Tambah Data</h4>

    <div class="card">
        <div class="card-body">
            <form id="formCreatUniversitas">
                <div class="mb-3">
                    <label for="kode" class="form-label">Kode</label>
                    <input type="text" id="kode" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="nama_universitas" class="form-label">Nama Universitas</label>
                    <input type="text" id="nama_universitas" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="alamat_universitas" class="form-label">Alamat Universitas</label>
                    <textarea id="alamat_universitas" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan
                </button>
                <a href="{{ route('admin.list_universitas.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            document.getElementById('formCreateUniversitas').addEventListener('submit', async e => {
                e.preventDefault();

                const payload = {
                    kode: document.getElementById('kode').value,
                    nama_universitas: document.getElementById('nama_universitas').value,
                    alamat_universitas: document.getElementById('alamat_universitas').value
                };

                try {
                    const res = await fetch('/api/list_universitas', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        throw new Error(data.message || 'Gagal menyimpan data.');
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('admin.list_universitas.index') }}";
                    });

                } catch (error) {
                    console.error(error);
                    Swal.fire('Gagal', error.message, 'error');
                }
            });
        });
    </script>
@endsection
