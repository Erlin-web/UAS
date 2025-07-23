@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Edit Pendaftaran</h4>

    <div class="card">
        <div class="card-body">
            <form id="formEditPendaftaran">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_user" class="form-label">Nama User</label>
                    <select id="id_user" class="form-select" required></select>
                </div>

                <div class="mb-3">
                    <label for="id_beasiswa" class="form-label">Nama Beasiswa</label>
                    <select id="id_beasiswa" class="form-select" required></select>
                </div>

                <div class="mb-3">
                    <label for="kode" class="form-label">Nama Universitas</label>
                    <select id="kode" class="form-select" required></select>
                </div>

                <div class="mb-3">
                    <label for="telp" class="form-label">Telp</label>
                    <input type="text" id="telp" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea id="alamat" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dokumen Lama</label>
                    <div id="daftar-dokumen-lama" class="d-flex flex-wrap gap-3"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Dokumen Baru</label>
                    <input type="file" name="dokumen_baru[]" id="dokumenBaru" class="form-control" multiple
                        accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                    <div id="previewDokumenBaru" class="d-flex flex-wrap gap-3 mt-2"></div>
                </div>


                <button type="submit" class="btn btn-warning">
                    <i class="bx bx-edit-alt"></i> Update
                </button>
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Kembali
                </a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Ambil ID dari URL → http://127.0.0.1:8000/admin/pendaftaran/1/edit
            const segments = window.location.pathname.split('/');
            const id_pendaftaran = segments[3];

            if (!id_pendaftaran || isNaN(id_pendaftaran)) {
                Swal.fire('Error', 'ID pendaftaran tidak valid di URL.', 'error');
                return;
            }

            // Helper untuk isi dropdown
            const fetchSelect = async (url, selectId, textKey, valueKey, selectedValue) => {
                try {
                    const res = await fetch(url, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    const select = document.getElementById(selectId);
                    select.innerHTML = '<option value="">-- Pilih --</option>';
                    if (data.data && Array.isArray(data.data)) {
                        data.data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item[valueKey];
                            option.textContent = item[textKey];
                            if (item[valueKey] == selectedValue) option.selected = true;
                            select.appendChild(option);
                        });
                    }
                } catch (e) {
                    console.error(`Gagal memuat ${selectId}:`, e);
                }
            };

            try {
                // Ambil data pendaftaran
                const res = await fetch(`/api/pendaftaran/${id_pendaftaran}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const status = res.status;

                const result = await res.json();
                if (!res.ok) {
                    throw new Error(result.message || `Gagal mengambil data pendaftaran (Status ${status})`);
                }

                const pendaftaran = result.data;

                if (!pendaftaran) {
                    Swal.fire('Error', 'Data pendaftaran tidak ditemukan.', 'error');
                    return;
                }

                // Load dropdown
                await Promise.all([
                    fetchSelect('/api/user', 'id_user', 'username', 'id_user', pendaftaran.id_user),
                    fetchSelect('/api/beasiswa', 'id_beasiswa', 'nama_beasiswa', 'id_beasiswa', pendaftaran.id_beasiswa),
                    fetchSelect('/api/list_universitas', 'kode', 'nama_universitas', 'kode', pendaftaran.kode),
                ]);

                // Isi field form
                document.getElementById('telp').value = pendaftaran.telp || '';
                document.getElementById('alamat').value = pendaftaran.alamat || '';

                // Tampilkan dokumen lama
                const container = document.getElementById('daftar-dokumen-lama');
                container.innerHTML = '';
                if (Array.isArray(pendaftaran.dokumen)) {
                    pendaftaran.dokumen.forEach(doc => {
                        const link = document.createElement('a');
                        link.href = doc.url ?? `/storage/${doc.nama_file}`;
                        link.textContent = doc.nama_file || 'Dokumen';
                        link.target = '_blank';
                        link.className = 'btn btn-sm btn-outline-primary me-2 mb-2';
                        container.appendChild(link);
                    });
                }

            } catch (error) {
                console.error('Gagal load data:', error);
                Swal.fire('Error', error.message || 'Terjadi kesalahan saat memuat data.', 'error');
            }

            // Preview dokumen baru
            document.getElementById('dokumenBaru').addEventListener('change', function (e) {
                const container = document.getElementById('previewDokumenBaru');
                container.innerHTML = '';
                [...e.target.files].forEach(file => {
                    const div = document.createElement('div');
                    div.className = 'border rounded p-2 mb-1';
                    div.innerText = file.name;
                    container.appendChild(div);
                });
            });

            // Submit form
            document.getElementById('formEditPendaftaran').addEventListener('submit', async e => {
                e.preventDefault();

                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('id_user', document.getElementById('id_user').value);
                formData.append('id_beasiswa', document.getElementById('id_beasiswa').value);
                formData.append('kode', document.getElementById('kode').value);
                formData.append('telp', document.getElementById('telp').value);
                formData.append('alamat', document.getElementById('alamat').value);

                const files = document.getElementById('dokumenBaru').files;
                for (let i = 0; i < files.length; i++) {
                    formData.append('dokumen[]', files[i]);
                }

                try {
                    const res = await fetch(`/api/pendaftaran/${id_pendaftaran}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const response = await res.json();
                    if (!res.ok) throw new Error(response.message || 'Gagal update data.');

                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        window.location.href = "{{ route('admin.pendaftaran.index') }}";
                    });
                } catch (err) {
                    console.error(err);
                    Swal.fire('Error', err.message || 'Gagal update data.', 'error');
                }
            });
        });
    </script>



@endsection
