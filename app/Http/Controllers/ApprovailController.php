<?php

namespace App\Http\Controllers;

use App\Models\Approvail;
use App\Models\Pendaftaran;
use App\Models\Verifikator;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class ApprovailController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $user = $request->user();

            if (!$user || !$user->role || $user->role->nama_role !== 'verifikator') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses sebagai verifikator.'
                ], 403);
            }

            $query = Pendaftaran::with(['user', 'beasiswa']);

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $data = $query->orderByDesc('created_at')->get();

            return response()->json([
                'success' => true,
                'message' => $request->has('status')
                    ? 'Pendaftaran dengan status ' . $request->status
                    : 'Semua data pendaftaran',
                'data' => $data
            ]);
        }, 'verifikator-list-pendaftaran');
    }

    public function approve(Request $request, $id_pendaftaran)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request, $id_pendaftaran) {
            $request->validate([
                'status' => 'required|in:Setujui,Tolak',
                'catatan' => 'nullable|string|max:1000',
            ]);

            $user = $request->user();
            $verifikator = Verifikator::where('id_user', $user->id_user)->first();

            if (!$verifikator) {
                return response()->json(['message' => 'Kamu bukan verifikator.'], 403);
            }

            // Cek apakah sudah pernah approve
            $sudahApprove = Approvail::where([
                ['id_pendaftaran', $id_pendaftaran],
                ['id_verifikator', $verifikator->id_verifikator]
            ])->exists();

            if ($sudahApprove) {
                return response()->json(['message' => 'Anda sudah memberikan keputusan.'], 400);
            }

            // Cek giliran verifikator berdasarkan tahapan
            $logSaatIni = Approvail::where('id_pendaftaran', $id_pendaftaran)->count();
            if ($logSaatIni + 1 !== $verifikator->tahapan) {
                return response()->json(['message' => 'Belum giliran Anda untuk verifikasi.'], 403);
            }

            // Simpan log approvail
            Approvail::create([
                'id_pendaftaran'   => $id_pendaftaran,
                'id_verifikator'   => $verifikator->id_verifikator,
                'status'           => $request->status,
                'catatan'          => $request->catatan
            ]);

            // Jika ditolak, langsung ubah status pendaftaran
            if ($request->status === 'Tolak') {
                Pendaftaran::where('id_pendaftaran', $id_pendaftaran)
                    ->update(['status' => 'Tolak']);
            } else {
                // Cek apakah ini verifikator terakhir (dengan tahapan tertinggi)
                $maxTahapan = Verifikator::max('tahapan');

                if ($verifikator->tahapan == $maxTahapan) {
                    // Ubah status pendaftaran jadi 'Setujui'
                    Pendaftaran::where('id_pendaftaran', $id_pendaftaran)
                        ->update(['status' => 'Setujui']);

                    // Tambahkan ke tabel persetujuan
                    \App\Models\Persetujuan::create([
                        'id_pendaftaran' => $id_pendaftaran,
                        'id_user'        => $user->id_user,
                        'status'         => 'Setujui',
                        'catatan'        => 'Pendaftaran disetujui oleh semua verifikator.'
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Keputusan Anda berhasil dicatat.'
            ]);
        }, 'approve-pendaftaran-verifikator');
    }



    public function show($id_pendafataran)
    {
        $pendaftaran = Pendaftaran::with(['user', 'beasiswa', 'list_universitas', 'dokumen'])->findOrFail($id_pendafataran);
        return view('pendaftaran.show', compact('pendaftaran'));
    }
}
