@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Edit Beasiswa</h4>

    <div class="card">
        <div class="card-body">
            <form id="formEdit">
                <div class="mb-3">
                    <label class="form-label">Nama Beasiswa</label>
                    <input type="text" class="form-control" id="nama_beasiswa" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Persyaratan</label>
                    <textarea class="form-control" id="persyaratan" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.beasiswa.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');
            const segments = window.location.pathname.split('/');
            const id_beasiswa = segments[3]; // ambil angka ID

            fetch(`/api/beasiswa/${id_beasiswa}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(result => {
                if (!result.data) {
                    throw new Error('Data beasiswa tidak ditemukan.');
                }

                document.getElementById('nama_beasiswa').value = result.data.nama_beasiswa;
                document.getElementById('deskripsi').value = result.data.deskripsi;
                document.getElementById('persyaratan').value = result.data.persyaratan;
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });

            document.getElementById('formEdit').addEventListener('submit', async function(e) {
                e.preventDefault();

                const payload = {
                    nama_beasiswa: document.getElementById('nama_beasiswa').value.trim(),
                    deskripsi: document.getElementById('deskripsi').value.trim(),
                    persyaratan: document.getElementById('persyaratan').value.trim(),
                };

                try {
                    const res = await fetch(`/api/beasiswa/${id_beasiswa}`, {
                        method: 'PUT',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await res.json();

                    if (!res.ok) {
                        throw new Error(result.message || 'Gagal mengupdate beasiswa');
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message
                    }).then(() => {
                        window.location.href = "{{ route('admin.beasiswa.index') }}";
                    });

                } catch (error) {
                    Swal.fire('Error', error.message, 'error');
                }
            });
        });
    </script>

@endsection
