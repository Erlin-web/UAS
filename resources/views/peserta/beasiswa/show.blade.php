@extends('peserta.index')

@section('content')
    <div class="container">
        <h4 class="fw-bold py-3 mb-4 text-center">Detail Beasiswa</h4>

        <div id="detail-beasiswa" class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body" style="background-color: #f8f9fa;">
                        <h5 id="nama-beasiswa" class="card-title fw-bold text-primary text-center mb-4">Memuat...</h5>
                        <p><strong>Deskripsi:</strong></p>
                        <p id="deskripsi" class="text-muted"></p>
                        <hr>
                        <p><strong>Persyaratan:</strong></p>
                        <p id="persyaratan" class="text-muted"></p>
                        <div class="mt-4 text-center">
                            <a href="/peserta/beasiswa" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
           const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Ambil ID dari URL (misalnya /peserta/beasiswa/5/show)
            const urlParts = window.location.pathname.split('/');
            const id = urlParts[urlParts.length - 2];

            fetch(`/api/beasiswa/${id}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Gagal memuat data beasiswa.');
                }
                return res.json();
            })
            .then(response => {
                const beasiswa = response.data;
                document.getElementById('nama-beasiswa').textContent = beasiswa.nama_beasiswa;
                document.getElementById('deskripsi').textContent = beasiswa.deskripsi;
                document.getElementById('persyaratan').textContent = beasiswa.persyaratan;
            })
            .catch(error => {
                console.error(error);
                document.getElementById('detail-beasiswa').innerHTML = `
                    <div class="col-12 text-center text-danger">Gagal memuat detail beasiswa</div>
                    <div class="col-12 text-center mt-3">
                        <a href="/peserta/beasiswa" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali
                        </a>
                    </div>
                `;
            });
        });
    </script>
@endsection
