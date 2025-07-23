<?php

namespace App\Http\Controllers;

use App\Models\ListUniversitas;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class ListUniversitasController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        return $this->transactionService->handleWithTransaction(function () {
            $list_universitas = ListUniversitas::all();

            return response()->json([
                'success' => true,
                'message' => 'Data semua universitas',
                'data' => $list_universitas
            ], 200);
        }, 'List Universitas');
    }

    public function store(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $validated = $request->validate([
                'NPSN' => 'required|unique:list_universitas,NPSN',
                'nama_universitas' => 'required|string|max:225',
                'alamat_universitas' => 'required|string|max:225',
            ]);

            $universitas = ListUniversitas::create($validated);

            $this->transactionService->handleWithLogDB(
                'store-universitas',
                'list_universitas',
                $universitas->NPSN,
                json_encode($universitas)
            );

            return response()->json([
                'success' => true,
                'message' => 'Data universitas berhasil disimpan',
                'data' => $universitas
            ]);
        }, 'Store Universitas');
    }

    public function show($npsn)
    {
        return $this->transactionService->handleWithTransaction(function () use ($npsn) {
            $list_universitas = ListUniversitas::find($npsn);

            if (!$list_universitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data universitas tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail universitas',
                'data' => $list_universitas
            ], 200);
        }, 'Show Universitas');
    }

    public function update(Request $request, $npsn)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request, $npsn) {
            $list_universitas = ListUniversitas::find($npsn);

            if (!$list_universitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data universitas tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'nama_universitas' => 'sometimes|required|string|max:225',
                'alamat_universitas' => 'sometimes|required|string|max:225',
            ]);

            $list_universitas->update($validated);

            $this->transactionService->handleWithLogDB(
                'update-universitas',
                'list_universitas',
                $npsn,
                json_encode($list_universitas)
            );

            return response()->json([
                'success' => true,
                'message' => 'Data universitas berhasil diperbarui',
                'data' => $list_universitas
            ], 200);
        }, 'Update Universitas');
    }

    public function destroy($npsn)
    {
        return $this->transactionService->handleWithTransaction(function () use ($npsn) {
            $list_universitas = ListUniversitas::find($npsn);

            if (!$list_universitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data universitas tidak ditemukan'
                ], 404);
            }

            $this->transactionService->handleWithLogDB(
                'delete-universitas',
                'list_universitas',
                $npsn,
                json_encode($list_universitas)
            );

            $list_universitas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Universitas berhasil dihapus'
            ], 200);
        }, 'Delete Universitas');
    }
}
