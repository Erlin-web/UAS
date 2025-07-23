@extends('admin.index')

@section('content')
<h4 class="fw-bold py-3 mb-4">Edit Verifikator</h4>

<div class="card p-4">
    <form id="formEditVerifikator">
        @csrf
        <input type="hidden" id="id_verifikator" name="id_verifikator" value="{{ $id_verifikator }}">

        <div class="mb-3">
            <label for="id_user" class="form-label">Nama User</label>
            <select id="id_user" name="id_user" class="form-select" required>
                <option value="">-- Pilih User --</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tahapan" class="form-label">Tahapan</label>
            <input type="number" id="tahapan" name="tahapan" class="form-control" min="1" placeholder="Contoh: 1" required>
        </div>

        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" id="jabatan" name="jabatan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" required>
                <option value="">-- Pilih Status --</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.verifikator.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        Swal.fire('Akses Ditolak', 'Silakan login ulang', 'error');
        return;
    }

    const idFromUrl = window.location.pathname.split('/').filter(Boolean).pop();
    document.getElementById('id_verifikator').value = idFromUrl;
    const id = idFromUrl;

    if (!id) {
        Swal.fire('Error', 'ID Verifikator tidak ditemukan di URL.', 'error');
        return;
    }

    // Load data Verifikator
    fetch(`/api/verifikator/${id}`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            const data = response.data;
            document.getElementById('tahapan').value = data.tahapan || '';
            document.getElementById('jabatan').value = data.jabatan || '';
            document.getElementById('status').value = data.status || '';
            loadUsers(data.id_user);
        } else {
            Swal.fire('Gagal', response.message || 'Data tidak ditemukan.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Gagal memuat data verifikator.', 'error');
    });

    // ...
});

    </script>
@endsection
