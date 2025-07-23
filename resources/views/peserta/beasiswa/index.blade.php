@extends('peserta.index')

@section('content')
    <div class="container">
        <h4 class="fw-bold py-3 mb-4 text-center">Jenis Beasiswa</h4>

        <div id="list-beasiswa" class="row g-4 justify-content-center">
            <div class="col-12 text-center text-muted">Memuat data...</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const token = localStorage.getItem('auth_token');

            // Tampilkan loading atau status sementara
            const listContainer = document.getElementById('list-beasiswa');
            listContainer.innerHTML = `
                <div class="col-12 text-center text-muted">Memuat data beasiswa...</div>
            `;

            if (!token) {
                listContainer.innerHTML = `
                    <div class="col-12 text-center text-danger">Anda belum login. Mengarahkan ke halaman login...</div>
                `;
                setTimeout(() => window.location.href = '/login', 2000);
                return;
            }

            try {
                const response = await fetch('/api/beasiswa', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 401) {
                    listContainer.innerHTML = `
                        <div class="col-12 text-center text-danger">Sesi Anda telah habis. Mengarahkan ke login...</div>
                    `;
                    setTimeout(() => window.location.href = '/login', 2000);
                    return;
                }

                if (!response.ok) {
                    throw new Error('Gagal memuat data beasiswa.');
                }

                const result = await response.json();
                const beasiswas = result.data;

                listContainer.innerHTML = ''; // Clear content

                if (!beasiswas || beasiswas.length === 0) {
                    listContainer.innerHTML = `
                        <div class="col-12 text-center text-muted">Data beasiswa kosong</div>
                    `;
                    return;
                }

                // Buat elemen kartu
                beasiswas.forEach((item) => {
                    const card = document.createElement('div');
                    card.className = 'col-md-6 col-lg-4';

                    card.innerHTML = `
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column justify-content-between" style="background-color: #f8f9fa;">
                                <div>
                                    <h5 class="card-title text-primary fw-bold mb-3 text-center">${item.nama_beasiswa}</h5>
                                    <p class="card-text mb-2">
                                        <span class="fw-semibold text-dark">Deskripsi:</span><br>
                                        <span class="text-muted">${item.deskripsi}</span>
                                    </p>
                                    <p class="card-text">
                                        <span class="fw-semibold text-dark">Persyaratan:</span><br>
                                        <span class="text-muted">${item.persyaratan}</span>
                                    </p>
                                </div>
                                <div class="mt-3">
                                    <a href="/peserta/beasiswa/${item.id_beasiswa}/show" class="btn btn-primary w-100">
                                        <i class="bx bx-show me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    listContainer.appendChild(card);
                });
            } catch (error) {
                console.error(error);
                listContainer.innerHTML = `
                    <div class="col-12 text-center text-danger">Terjadi kesalahan saat memuat data beasiswa</div>
                `;
            }
        });
    </script>

@endsection
