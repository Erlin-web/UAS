<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Pendaftaran;
use App\Services\TransactionService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        return $this->transactionService->handleWithTransaction(function () {
            $pendaftaran = Pendaftaran::with(['user.role', 'beasiswa', 'list_universitas'])
                ->whereHas('user.role', function ($query) {
                    $query->where('nama_role', 'peserta');
                })
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data pendaftaran (peserta)',
                'data' => $pendaftaran
            ], 200);
        }, 'List Pendaftaran');
    }


    public function store(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $validatedData = $request->validate([
                'id_user' => 'required|exists:user,id_user',
                'id_beasiswa' => 'required|exists:beasiswa,id_beasiswa',
                'kode' => 'required|exists:list_universitas,kode',
                'telp' => 'required|string',
                'alamat' => 'required|string',
            ]);

            $pendaftaran = Pendaftaran::create($validatedData);

            $this->transactionService->handleWithLogDB(
                'store-pendaftaran',
                'pendaftaran',
                $pendaftaran->id_pendaftaran,
                json_encode($pendaftaran)
            );

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil ditambahkan!',
                'data' => $pendaftaran
            ], 201);
        }, 'Store Pendaftaran');
    }

    public function show($id_pendaftaran)
    {
        $pendaftaran = Pendaftaran::with(['user', 'beasiswa', 'list_universitas', 'dokumen'])->find($id_pendaftaran);

        if (!$pendaftaran) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Tambahkan URL untuk setiap dokumen
        $pendaftaran->dokumen->transform(function ($dokumen) {
            $dokumen->url = asset('storage/' . $dokumen->nama_file);
            return $dokumen;
        });

        return response()->json([
            'success' => true,
            'data' => $pendaftaran
        ]);
    }


    public function update(Request $request, $id_pendaftaran)
    {
        $request->validate([
            'id_user' => 'sometimes|exists:user,id_user',
            'id_beasiswa' => 'sometimes|exists:beasiswa,id_beasiswa',
            'kode' => 'sometimes|exists:list_universitas,kode',
            'telp' => 'sometimes|string',
            'alamat' => 'sometimes|string',
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        return $this->transactionService->handleWithTransaction(function () use ($request, $id_pendaftaran) {
            $pendaftaran = Pendaftaran::findOrFail($id_pendaftaran);
            $pendaftaran->update($request->except('dokumen'));

            // Hapus dokumen lama jika perlu
            if ($request->hasFile('dokumen')) {
                // Hapus dokumen lama dari storage
                foreach ($pendaftaran->dokumen as $dokumen) {
                    Storage::disk('public')->delete($dokumen->nama_file);
                    $dokumen->delete();
                }

                foreach ($request->file('dokumen') as $file) {
                    $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen', $filename, 'public');

                    Dokumen::create([
                        'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                        'nama_file' => $path
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pendaftaran berhasil diperbarui!',
                'data' => $pendaftaran->load('dokumen')
            ]);
        }, 'Update Pendaftaran');
    }




    public function destroy($id_pendaftaran)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_pendaftaran) {
            $pendaftaran = Pendaftaran::find($id_pendaftaran);

            if (!$pendaftaran) {
                return response()->json(['success' => false, 'message' => 'Pendaftaran tidak ditemukan.'], 404);
            }

            // Hapus semua dokumen
            $dokumen = Dokumen::where('id_pendaftaran', $id_pendaftaran)->get();
            foreach ($dokumen as $d) {
                if ($d->nama_file && Storage::disk('public')->exists($d->nama_file)) {
                    Storage::disk('public')->delete($d->nama_file);
                }
                $d->delete();
            }

            $pendaftaran->delete();

            return response()->json(['success' => true, 'message' => 'Pendaftaran dan dokumen terkait berhasil dihapus.']);
        }, 'delete-pendaftaran');
    }
}
