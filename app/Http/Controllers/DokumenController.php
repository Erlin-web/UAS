<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    // Ambil semua dokumen
    public function index()
    {
        return $this->transactionService->handleWithTransaction(function () {
            $dokumen = Dokumen::with('pendaftaran')->get();

            return response()->json([
                'success' => true,
                'message' => 'List semua dokumen',
                'data' => $dokumen
            ]);
        }, 'List Dokumen');
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required|exists:pendaftaran,id_pendaftaran',
            'dokumen.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $files = $request->file('dokumen');
        $savedFiles = [];

        foreach ($files as $file) {
            $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // ✅ Simpan ke storage/app/public/dokumen
            $file->storeAs('dokumen', $filename, 'public');

            // Simpan nama file relatif ke DB
            $dokumen = Dokumen::create([
                'id_pendaftaran' => $request->id_pendaftaran,
                'nama_file' => 'dokumen/' . $filename, // penting!
            ]);

            $savedFiles[] = $dokumen;
        }

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil disimpan.',
            'data' => $savedFiles
        ]);
    }

    // Simpan dokumen baru
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'id_pendaftaran' => 'required|exists:pendaftaran,id_pendaftaran',
    //         'dokumen' => 'required|array',
    //         'dokumen.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ], [
    //         'id_pendaftaran.required' => 'ID pendaftaran wajib diisi.',
    //         'id_pendaftaran.exists' => 'Pendaftaran tidak ditemukan.',
    //         'dokumen.required' => 'File dokumen wajib diunggah.',
    //         'dokumen.*.file' => 'File tidak valid.',
    //         'dokumen.*.mimes' => 'Format file harus PDF/JPG/PNG.',
    //         'dokumen.*.max' => 'Ukuran maksimal file 2MB.'
    //     ]);

    //     return $this->transactionService->handleWithTransaction(function () use ($request) {
    //         $dokumenData = [];

    //         if ($request->hasFile('dokumen')) {
    //             foreach ($request->file('dokumen') as $file) {
    //                 $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
    //                 $path = $file->storeAs('dokumen', $filename, 'public');

    //                 // simpan path ke nama_file (karena tidak ada field path_file)
    //                 $dokumen = Dokumen::create([
    //                     'id_pendaftaran' => $request->id_pendaftaran,
    //                     'nama_file'      => $path, // simpan path lengkap
    //                 ]);

    //                 $dokumenData[] = $dokumen;
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Dokumen berhasil diunggah.',
    //             'data' => $dokumenData
    //         ]);
    //     }, 'store-dokumen');
    // }

    // Detail dokumen
    public function show($id_dokumen)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_dokumen) {
            $dokumen = Dokumen::with('pendaftaran')->find($id_dokumen);

            if (!$dokumen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan.'
                ], 404);
            }
            $dokumen->url = asset('storage/' . $dokumen->nama_file);

            return response()->json([
                'success' => true,
                'message' => 'Detail dokumen',
                'data' => $dokumen
            ]);
        }, 'Detail Dokumen');
    }

    // Update dokumen
    public function update(Request $request, $id_dokumen)
    {
        $request->validate([
            'id_pendaftaran' => 'nullable|exists:pendaftaran,id_pendaftaran',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:2048'
        ]);

        return $this->transactionService->handleWithTransaction(function () use ($request, $id_dokumen) {
            $dokumen = Dokumen::find($id_dokumen);

            if (!$dokumen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan.'
                ], 404);
            }

            if ($request->hasFile('file')) {
                // hapus file lama
                if ($dokumen->nama_file) {
                    Storage::disk('public')->delete($dokumen->nama_file);
                }

                $file = $request->file('file');
                $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('dokumen', $filename, 'public');

                $dokumen->nama_file = $path;
            }

            $dokumen->id_pendaftaran = $request->id_pendaftaran ?? $dokumen->id_pendaftaran;
            $dokumen->save();

            $this->transactionService->handleWithLogDB(
                'Update Dokumen',
                'dokumen',
                $dokumen->id_dokumen,
                json_encode($dokumen)
            );

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diperbarui!',
                'data' => $dokumen
            ]);
        }, 'Update Dokumen');
    }

    public function updateByPendaftaran(Request $request, $id_pendaftaran)
    {
        $request->validate([
            'dokumen.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        return $this->transactionService->handleWithTransaction(function () use ($request, $id_pendaftaran) {
            // Ambil semua dokumen lama
            $dokumenLama = Dokumen::where('id_pendaftaran', $id_pendaftaran)->get();

            // Hapus file lama
            foreach ($dokumenLama as $d) {
                if ($d->nama_file && Storage::disk('public')->exists($d->nama_file)) {
                    Storage::disk('public')->delete($d->nama_file);
                }
                $d->delete();
            }

            // Simpan dokumen baru
            $dokumenBaru = [];
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $file) {
                    $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen', $filename, 'public');

                    $dokumen = Dokumen::create([
                        'id_pendaftaran' => $id_pendaftaran,
                        'nama_file' => $path // disimpan path lengkap seperti: dokumen/20250722_xyz.pdf
                    ]);

                    $dokumenBaru[] = $dokumen;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diperbarui.',
                'data' => $dokumenBaru
            ]);
        }, 'update-dokumen-by-pendaftaran');
    }


    // Hapus dokumen
    public function destroy($id_dokumen)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_dokumen) {
            $dokumen = Dokumen::find($id_dokumen);

            if (!$dokumen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan.'
                ], 404);
            }

            // DEBUG LOG
            Log::info('Dokumen ditemukan:', [
                'nama_file' => $dokumen->nama_file,
                'exists' => Storage::disk('public')->exists($dokumen->nama_file)
            ]);

            // Hapus file jika ada
            if ($dokumen->nama_file && Storage::disk('public')->exists($dokumen->nama_file)) {
                Storage::disk('public')->delete($dokumen->nama_file);
            }

            $this->transactionService->handleWithLogDB(
                'delete-dokumen',
                'dokumen',
                $dokumen->id_dokumen,
                json_encode(['deleted' => true, 'nama_file' => $dokumen->nama_file])
            );

            $dokumen->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus!'
            ]);
        }, 'delete-dokumen');
    }
}
