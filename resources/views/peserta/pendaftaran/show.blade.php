@extends('peserta.index')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Detail Pendaftaran</h4>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nama User</dt>
                <dd class="col-sm-9" id="show_username">-</dd>

                <dt class="col-sm-3">Nama Beasiswa</dt>
                <dd class="col-sm-9" id="show_beasiswa">-</dd>

                <dt class="col-sm-3">Nama Universitas</dt>
                <dd class="col-sm-9" id="show_universitas">-</dd>

                <dt class="col-sm-3">Telp</dt>
                <dd class="col-sm-9" id="show_telp">-</dd>

                <dt class="col-sm-3">Alamat</dt>
                <dd class="col-sm-9" id="show_alamat">-</dd>

                <dt class="col-sm-3">Dokumen</dt>
                <dd class="col-sm-9" id="show_dokumen">-</dd>
            </dl>

            <a href="{{ route('peserta.pendaftaran.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Ambil ID pendaftaran dari URL
            const pathParts = window.location.pathname.split('/');
            const id = pathParts[pathParts.length - 1];

            try {
                const res = await fetch(`/api/pendaftaran/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (res.status === 401) {
                    window.location.href = '/login';
                    return;
                }

                const response = await res.json();

                if (res.ok && response.success) {
                    const data = response.data;

                    document.getElementById('show_username').textContent = data.user?.username || '-';
                    document.getElementById('show_beasiswa').textContent = data.beasiswa?.nama_beasiswa || '-';
                    document.getElementById('show_universitas').textContent = data.list_universitas?.nama_universitas || '-';
                    document.getElementById('show_telp').textContent = data.telp || '-';
                    document.getElementById('show_alamat').textContent = data.alamat || '-';


                    // Dokumen
                    if (data.dokumen && data.dokumen.length > 0) {
                        let dokumenHtml = "";
                        data.dokumen.forEach(file => {
                            dokumenHtml += `<a href="${file.url}" target="_blank" class="d-block mb-1">${file.nama_file}</a>`;
                        });
                        document.getElementById('show_dokumen').innerHTML = dokumenHtml;
                    } else {
                        document.getElementById('show_dokumen').textContent = '-';
                    }

                } else {
                    Swal.fire('Error', response.message || 'Data tidak ditemukan.', 'error');
                }

            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Gagal mengambil data.', 'error');
            }
        });
    </script>
@endsection
