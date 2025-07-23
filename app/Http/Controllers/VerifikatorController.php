<?php

namespace App\Http\Controllers;

use App\Models\Verifikator;
use Illuminate\Http\Request;
use App\Services\TransactionService;

class VerifikatorController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Tampilkan semua verifikator.
     */
    public function index() {
        return $this->transactionService->handleWithTransaction(function() {
            $verifikator = Verifikator::with('user')->get();
            return response()->json([
                'success' => true,
                'message' => 'List semua data verifikator',
                'data' => $verifikator
            ], 200);
        }, 'list-verifikator');
    }

    /**
     * Simpan data verifikator baru.
     */
    public function store(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $validated = $request->validate([
                'id_user' => 'required|exists:user,id_user|unique:verifikator,id_user',
                'tahapan' => 'nullable|string|max:255',
                'jabatan' => 'nullable|string|max:255',
                'status' => 'required|in:aktif,nonaktif'
            ]);

            $verifikator = Verifikator::create($validated);

            $this->transactionService->handleWithLogDB(
                'store-verifikator',
                'verifikator',
                $verifikator->id_verifikator,
                json_encode($verifikator)
            );

            return response()->json([
                'success' => true,
                'message' => 'Data verifikator berhasil ditambahkan.',
                'data' => $verifikator
            ], 201);
        }, 'Store Verifikator');
    }


    /**
     * Tampilkan detail verifikator tertentu.
     */
    public function show($id_verifikator)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_verifikator) {
            $verifikator = Verifikator::with('user')->find($id_verifikator);

            if (!$verifikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data verifikator tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail data verifikator.',
                'data' => $verifikator
            ]);
        }, 'Show Verifikator');
    }

    /**
     * Update data verifikator.
     */
    public function update(Request $request, $id_verifikator)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request, $id_verifikator) {
            $verifikator = Verifikator::find($id_verifikator);

            if (!$verifikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data verifikator tidak ditemukan.'
                ], 404);
            }

            $validated = $request->validate([
                'id_user' => 'nullable|exists:user,id_user',
                'tahapan' => 'nullable|string|max:255',
                'jabatan' => 'nullable|string|max:255',
                'status' => 'required|in:aktif,nonaktif'
            ]);

            $verifikator->update($validated);

            $this->transactionService->handleWithLogDB(
                'update-verifikator',
                'verifikator',
                $verifikator->id_verifikator,
                json_encode($verifikator)
            );

            return response()->json([
                'success' => true,
                'message' => 'Data verifikator berhasil diupdate.',
                'data' => $verifikator
            ]);
        }, 'Update Verifikator');
    }

    /**
     * Hapus data verifikator.
     */
    public function destroy($id_verifikator)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_verifikator) {
            $verifikator = Verifikator::find($id_verifikator);

            if (!$verifikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data verifikator tidak ditemukan.'
                ], 404);
            }

            $this->transactionService->handleWithLogDB(
                'delete-verifikator',
                'verifikator',
                $verifikator->id_verifikator,
                json_encode($verifikator)
            );

            $verifikator->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data verifikator berhasil dihapus.'
            ]);
        }, 'Delete Verifikator');
    }
}
