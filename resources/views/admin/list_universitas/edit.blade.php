@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Edit Data</h4>

    <div class="card">
        <div class="card-body">
            <form id="formEditUniversitas">
                <div class="mb-3">
                    <label for="kode" class="form-label">Kode</label>
                    <input type="text" id="kode" class="form-control" readonly>
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
                    <i class="bx bx-save"></i> Update
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

            const currentkode= '{{ $kode}}';

            fetch(`/api/list_universitas/${currentNPSN}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    const universitas = response.data;
                    document.getElementById('kode').value = universitas.NPSN;
                    document.getElementById('nama_universitas').value = universitas.nama_universitas;
                    document.getElementById('alamat_universitas').value = universitas.alamat_universitas;
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire('Error', 'Gagal mengambil data universitas.', 'error');
            });

            document.getElementById('formEditUniversitas').addEventListener('submit', async e => {
                e.preventDefault();

                const payload = {
                    nama_universitas: document.getElementById('nama_universitas').value,
                    alamat_universitas: document.getElementById('alamat_universitas').value
                };

                try {
                    const res = await fetch(`/api/list_universitas/${currentkode}`, {
                        method: 'PUT',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        throw new Error(data.message || 'Gagal memperbarui data.');
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
                    Swal.fire('Error', error.message, 'error');
                }
            });
        });
    </script>
@endsection
