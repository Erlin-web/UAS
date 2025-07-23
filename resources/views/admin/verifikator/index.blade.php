@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Data Verifikator</h4>

    <div class="card border-start border-primary border-4 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-semibold">Daftar Verifikator</h5>
            <a href="{{ route('admin.verifikator.create') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus"></i> Tambah Verifikator
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th>Nama User</th>
                        <th>Tahapan</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-verifikator">
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <div class="spinner-border text-primary" role="status"></div>
                            <br>Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');
            console.log("Token yang digunakan:", token);

            if (!token) {
                window.location.href = '/login';
                return;
            }

            loadVerifikator(token);
        });

        function loadVerifikator(token) {
            fetch('/api/verifikator', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        console.error("HTTP error:", res.status);
                        return res.text().then(text => {
                            console.error("Response Text:", text);
                            throw new Error(`HTTP error ${res.status}`);
                        });
                    }
                    return res.json();
                })
                .then(response => {
                    console.log("Response data:", response);

                    const tbody = document.getElementById("tabel-verifikator");
                    if (!tbody) {
                        console.error("Element #tabel-verifikator tidak ditemukan di halaman.");
                        return;
                    }

                    tbody.innerHTML = "";

                    if (response.success && response.data.length > 0) {
                        response.data.forEach((verifikator, index) => {
                            tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${verifikator.user?.username ?? '-'}</td>
                            <td>${verifikator.tahapan ?? '-'}</td>
                            <td>${verifikator.jabatan ?? '-'}</td>
                            <td>${verifikator.status}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" onclick="showVerifikator(${verifikator.id_verifikator})">
                                    <i class="bx bx-show"></i>
                                </button>
                                <a href="/admin/verifikator/${verifikator.id_verifikator}/edit" class="btn btn-sm btn-outline-warning">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" onclick="hapusVerifikator(${verifikator.id_verifikator})">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                        });
                    } else {
                        tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted">Data verifikator belum tersedia.</td>
                    </tr>
                `;
                    }
                })
                .catch(error => {
                    console.error("Gagal mengambil data verifikator:", error);
                    alert("Error memuat data: " + error.message);
                    document.getElementById("tabel-verifikator").innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger">Gagal memuat data verifikator.</td>
                </tr>
            `;
                });
        }

        function showVerifikator(id_verifikator) {
            window.location.href = `/admin/verifikator/${id_verifikator}`;
        }

        function hapusVerifikator(id_verifikator) {
            const token = localStorage.getItem('auth_token');
            Swal.fire({
                title: 'Yakin hapus data?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const res = await fetch(`/api/verifikator/${id_verifikator}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();
                    if (res.ok) {
                        Swal.fire('Berhasil', json.message, 'success').then(() => {
                            loadVerifikator(token);
                        });
                    } else {
                        Swal.fire('Gagal', json.message, 'error');
                    }
                }
            });
        }
    </script>
@endsection
