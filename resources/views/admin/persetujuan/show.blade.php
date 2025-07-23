@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Detail Persetujuan</h4>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID Persetujuan</dt>
                <dd class="col-sm-9" id="show_id_persetujuan">-</dd>

                <dt class="col-sm-3">ID Pendaftaran</dt>
                <dd class="col-sm-9" id="show_id_pendaftaran">-</dd>

                <dt class="col-sm-3">Nama User</dt>
                <dd class="col-sm-9" id="show_nama_user">-</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9" id="show_status">-</dd>

                <dt class="col-sm-3">Catatan</dt>
                <dd class="col-sm-9" id="show_catatan">-</dd>
            </dl>

            <a href="{{ route('admin.persetujuan.index') }}" class="btn btn-secondary">
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

            // Ambil ID persetujuan dari URL
            const pathParts = window.location.pathname.split('/');
            const id = pathParts[pathParts.length - 1];

            try {
                const res = await fetch(`/api/persetujuan/${id}`, {
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

                    document.getElementById('show_id_persetujuan').textContent = data.id_persetujuan ?? '-';
                    document.getElementById('show_id_pendaftaran').textContent = data.pendaftaran?.id_pendaftaran ?? '-';
                    document.getElementById('show_nama_user').textContent = data.user?.username ?? '-';
                    document.getElementById('show_status').textContent = data.status ?? '-';
                    document.getElementById('show_catatan').textContent = data.catatan ?? '-';

                } else {
                    Swal.fire('Error', response.message || 'Data tidak ditemukan.', 'error');
                }

            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Terjadi kesalahan saat mengambil data.', 'error');
            }
        });
    </script>
@endsection
