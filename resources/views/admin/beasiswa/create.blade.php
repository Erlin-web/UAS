@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Tambah Beasiswa</h4>

    <div class="card">
        <div class="card-body">
            <form id="formCreateBeasiswa">
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

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.beasiswa.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

   <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formCreateBeasiswa');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const token = localStorage.getItem('auth_token');
            if (!token) {
                Swal.fire('Error', 'Token tidak ditemukan. Silakan login kembali.', 'error');
                return;
            }

            const data = {
                nama_beasiswa: document.getElementById('nama_beasiswa').value,
                deskripsi: document.getElementById('deskripsi').value,
                persyaratan: document.getElementById('persyaratan').value,
            };

            fetch('/api/beasiswa', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
            })
                .then(async (response) => {
                    const res = await response.json();

                    if (response.ok) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: res.message || 'Beasiswa berhasil disimpan.',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = "{{ route('admin.beasiswa.index') }}";
                        });
                    } else {
                        // Validasi Laravel (422)
                        if (res.errors) {
                            let errorMessages = '';
                            Object.keys(res.errors).forEach(key => {
                                errorMessages += `- ${res.errors[key].join(', ')}<br>`;
                            });

                            Swal.fire({
                                title: 'Validasi Gagal',
                                html: errorMessages,
                                icon: 'error'
                            });
                        } 
                        // Error umum dari backend
                        else {
                            Swal.fire({
                                title: 'Gagal',
                                text: res.message || 'Terjadi kesalahan.',
                                footer: res.error || '', // tampilkan pesan error backend
                                icon: 'error'
                            });
                        }
                    }
                })
                .catch((error) => {
                    console.error('Fetch Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat mengirim data.', 'error');
                });
        });
    });
    </script>



@endsection
