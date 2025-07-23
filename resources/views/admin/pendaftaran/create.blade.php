@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Tambah Pendaftaran</h4>

    <div class="card">
        <div class="card-body">
            <form id="formCreatePendaftaran" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="id_user" class="form-label">User (Peserta)</label>
                    <select class="form-select" id="id_user" name="id_user" required></select>
                </div>

                <div class="mb-3">
                    <label for="id_beasiswa" class="form-label">Beasiswa</label>
                    <select class="form-select" id="id_beasiswa" name="id_beasiswa" required></select>
                </div>

                <div class="mb-3">
                    <label for="kode" class="form-label">Universitas</label>
                    <select class="form-select" id="kode" name="kode" required></select>
                </div>

                <div class="mb-3">
                    <label for="telp" class="form-label">No. Telp</label>
                    <input type="text" class="form-control" id="telp" name="telp" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                </div>

                <div class="mb-3">
                    <label for="dokumen" class="form-label">Unggah Dokumen</label>
                    <input type="file" class="form-control" id="dokumen" name="dokumen[]" multiple
                        accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                Swal.fire('Error', 'Token tidak ditemukan. Silakan login ulang.', 'error');
                return;
            }

            /**
             * Load dropdown options from API
             */
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

            // ✅ Load hanya user dengan role = peserta
            loadSelect('/api/user?role=peserta', 'id_user',
                user => `${user.id_user || user.id} - ${user.username || user.name}`, 'id_user');

            loadSelect('/api/beasiswa', 'id_beasiswa',
                b => `${b.id_beasiswa || b.id} - ${b.nama_beasiswa || b.nama}`, 'id_beasiswa');

            loadSelect('/api/list_universitas', 'kode',
                u => `${u.kode} - ${u.nama_universitas || u.nama}`, 'kode');

            /**
             * Submit pendaftaran
             */
            document.getElementById('formCreatePendaftaran').addEventListener('submit', async function (e) {
                e.preventDefault();
                const form = e.target;

                try {
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
                        throw new Error(response.message || 'Gagal menyimpan pendaftaran.');
                    }

                    const idPendaftaran = response.data?.id_pendaftaran;
                    if (!idPendaftaran) throw new Error('ID pendaftaran tidak ditemukan.');

                    const files = document.getElementById('dokumen').files;
                    if (files.length > 0) {
                        const formData = new FormData();
                        formData.append('id_pendaftaran', idPendaftaran);
                        for (let i = 0; i < files.length; i++) {
                            formData.append('dokumen[]', files[i]);
                        }

                        const resDokumen = await fetch('/api/dokumen', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const responseDokumen = await resDokumen.json();
                        if (!resDokumen.ok || !responseDokumen.success) {
                            throw new Error(responseDokumen.message || 'Gagal upload dokumen.');
                        }
                    }

                    Swal.fire('Berhasil', 'Pendaftaran berhasil disimpan.', 'success')
                        .then(() => window.location.href = '{{ route("admin.pendaftaran.index") }}');

                } catch (error) {
                    console.error("ERROR submit:", error);
                    Swal.fire('Error', error.message || 'Terjadi kesalahan pada sistem.', 'error');
                }
            });
        });
    </script>
@endsection
