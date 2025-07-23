@extends('peserta.index')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Formulir Pendaftaran</h4>

        <form id="formCreatePendaftaran" enctype="multipart/form-data">
            @csrf

            <div id="previewDokumen" class="d-flex flex-wrap mt-3"></div>
            <div class="mb-3">
                <label for="id_user" class="form-label">Peserta</label>
                <select id="id_user" class="form-select" name="id_user" required></select>
            </div>

            <div class="mb-3">
                <label for="id_beasiswa" class="form-label">Beasiswa</label>
                <select id="id_beasiswa" class="form-select" name="id_beasiswa" required></select>
            </div>

            <div class="mb-3">
                <label for="kode" class="form-label">Universitas</label>
                <select id="kode" class="form-select" name="kode" required></select>
            </div>

            <div class="mb-3">
                <label for="telp" class="form-label">No. Telepon</label>
                <input type="text" id="telp" name="telp" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="dokumen" class="form-label">Unggah Dokumen (PDF/JPG/PNG, max 2MB per file)</label>
                <input type="file" id="dokumen" name="dokumen[]" class="form-control" multiple required>
            </div>

            <div id="previewDokumen" class="d-flex flex-wrap mt-2"></div>

            <button type="submit" class="btn btn-primary mt-3">Submit Pendaftaran</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                Swal.fire('Error', 'Token tidak ditemukan. Silakan login ulang.', 'error');
                return;
            }

            function handleTokenError(message) {
                if (message?.includes('token expired') || message?.includes('unauthenticated')) {
                    Swal.fire('Sesi Habis', 'Silakan login ulang.', 'warning').then(() => {
                        localStorage.removeItem('auth_token');
                        window.location.href = '/login';
                    });
                    return true;
                }
                return false;
            }

            function loadSelect(endpoint, selectId, textFormatter, idKey = 'id') {
                fetch(endpoint, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const select = document.getElementById(selectId);
                        if (!select) return;

                        select.innerHTML = `<option value="">Pilih ${selectId.replace('_', ' ')}</option>`;

                        if (!data || !data.data || data.data.length === 0) {
                            Swal.fire('Info', `Data untuk ${selectId} tidak ditemukan.`, 'info');
                            return;
                        }

                        data.data.forEach(item => {
                            const value = item[idKey] || item.id;
                            const text = textFormatter(item);
                            if (value) {
                                select.appendChild(new Option(text, value));
                            }
                        });
                    })
                    .catch(err => {
                        console.error(`Gagal memuat ${selectId}:`, err);
                        Swal.fire('Error', `Gagal memuat data ${selectId}.`, 'error');
                    });
            }

            // Load data dropdown
            loadSelect('/api/user?role=peserta', 'id_user', user =>
                `${user.id_user || user.id} - ${user.username || user.name}`, 'id_user');
            loadSelect('/api/beasiswa', 'id_beasiswa', b =>
                `${b.id_beasiswa || b.id} - ${b.nama_beasiswa || b.nama}`, 'id_beasiswa');
            loadSelect('/api/list_universitas', 'kode', u =>
                `${u.kode} - ${u.nama_universitas || u.nama}`, 'kode');

            // Form submit
            document.getElementById('formCreatePendaftaran').addEventListener('submit', async function(e) {
                e.preventDefault();
                const form = e.target;

                try {
                    // 1. Simpan data pendaftaran
                    const payload = {
                        id_user: form.id_user.value,
                        id_beasiswa: form.id_beasiswa.value,
                        kode: form.kode.value,
                        telp: form.telp.value,
                        alamat: form.alamat.value,
                    };

                    const res = await fetch('/api/pendaftaran', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const response = await res.json();
                    if (!res.ok || !response.success) {
                        if (handleTokenError(response.message)) return;
                        throw new Error(response.message || 'Gagal menyimpan pendaftaran.');
                    }

                    const idPendaftaran = response.data?.id_pendaftaran;
                    if (!idPendaftaran) throw new Error('ID pendaftaran tidak ditemukan.');

                    // 2. Upload dokumen ke storage
                    const dokumenInput = document.getElementById('dokumen');
                    if (dokumenInput.files.length > 0) {
                        const formData = new FormData();
                        formData.append('id_pendaftaran', idPendaftaran);

                        for (let i = 0; i < dokumenInput.files.length; i++) {
                            formData.append('dokumen[]', dokumenInput.files[i]);
                        }

                        const dokumenRes = await fetch('/api/dokumen', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${token}`
                            },
                            body: formData
                        });

                        const dokumenJson = await dokumenRes.json();
                        if (!dokumenRes.ok || !dokumenJson.success) {
                            if (handleTokenError(dokumenJson.message)) return;
                            throw new Error(dokumenJson.message || 'Gagal mengunggah dokumen.');
                        }
                    }

                    // 3. Sukses semua
                    Swal.fire('Berhasil', 'Pendaftaran berhasil disimpan.', 'success')
                        .then(() => window.location.href = '{{ route('peserta.pendaftaran.index') }}');

                } catch (error) {
                    console.error("ERROR submit:", error);
                    Swal.fire('Error', error.message || 'Terjadi kesalahan pada sistem.', 'error');
                }
            });
        });
    </script>
@endsection
