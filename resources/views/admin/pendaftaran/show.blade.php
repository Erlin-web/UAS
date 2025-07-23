@extends('admin.layout')

@section('content')
    <h4 class="fw-bold py-3 mb-4">Detail Pendaftaran</h4>

    <div class="card">
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th style="width: 200px">Nama User</th>
                    <td>{{ $pendaftaran->user->username ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nama Beasiswa</th>
                    <td>{{ $pendaftaran->beasiswa->nama_beasiswa ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nama Universitas</th>
                    <td>{{ $pendaftaran->list_universitas->nama_universitas ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Telp</th>
                    <td>{{ $pendaftaran->telp ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $pendaftaran->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-{{ $pendaftaran->status == 'disetujui' ? 'success' : ($pendaftaran->status == 'ditolak' ? 'danger' : 'warning') }}">
                            {{ ucfirst($pendaftaran->status) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Daftar</th>
                    <td>{{ \Carbon\Carbon::parse($pendaftaran->created_at)->translatedFormat('d F Y H:i') }}</td>
                </tr>
            </table>

            @if ($pendaftaran->dokumen && count($pendaftaran->dokumen))
                <div class="mt-4">
                    <h5>Dokumen Terlampir:</h5>
                    <ul class="list-group">
                        @foreach ($pendaftaran->dokumen as $dok)
                            <li class="list-group-item">
                                <strong>{{ $dok->nama_file }}</strong>
                                <div class="mt-2">
                                    @php
                                        $ext = pathinfo($dok->nama_file, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                                    @endphp

                                    @if ($isImage)
                                        <img src="{{ asset('storage/' . $dok->path_file) }}" alt="Dokumen Gambar" class="img-fluid mb-2" style="max-height: 300px;">
                                    @else
                                        <a href="{{ asset('storage/' . $dok->path_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-download"></i> Lihat PDF
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Kembali
                </a>
                <a href="{{ route('admin.pendaftaran.edit', $pendaftaran->id_pendaftaran) }}" class="btn btn-warning">
                    <i class="bx bx-edit-alt"></i> Edit
                </a>
            </div>
        </div>
    </div>
@endsection
