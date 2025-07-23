<?php

namespace App\Http\Controllers;

use App\Models\Persetujuan;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class PersetujuanController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        return $this->transactionService->handleWithTransaction(function () {
            $persetujuan = Persetujuan::with(['pendaftaran', 'user'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Data persetujuan',
                'data' => $persetujuan
            ], 200);
        }, 'List Persetujuan');
    }

    public function store(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $validatedData = $request->validate([
                'id_pendaftaran' => 'required|exists:pendaftaran,id_pendaftaran',
                'id_user' => 'required|exists:user,id_user',
                'status' => 'required|in:proses,setujui,tolak',
                'catatan' => 'required|string',
            ]);

            $persetujuan = Persetujuan::create($validatedData);

            $this->transactionService->handleWithLogDB(
                'store-persetujuan',
                'persetujuan',
                $persetujuan->id_persetujuan,
                json_encode($persetujuan)
            );

            return response()->json([
                'success' => true,
                'message' => 'Persetujuan berhasil ditambahkan!',
                'data' => $persetujuan
            ], 201);
        }, 'Store Persetujuan');
    }

    public function show($id_persetujuan)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_persetujuan) {
            $persetujuan = Persetujuan::with(['pendaftaran', 'user'])
                ->where('id_persetujuan', $id_persetujuan)
                ->first();

            if (!$persetujuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data persetujuan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail data persetujuan',
                'data' => $persetujuan
            ], 200);
        }, 'Show Persetujuan');
    }

    public function update(Request $request, $id_persetujuan)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request, $id_persetujuan) {
            $persetujuan = Persetujuan::find($id_persetujuan);

            if (!$persetujuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data persetujuan tidak ditemukan'
                ], 404);
            }

            $validatedData = $request->validate([
                'id_pendaftaran' => 'sometimes|required|exists:pendaftaran,id_pendaftaran',
                'id_user' => 'sometimes|required|exists:user,id_user',
                'status' => 'sometimes|required|in:proses,setujui,tolak',
                'catatan' => 'sometimes|required|string',
            ]);

            $persetujuan->update($validatedData);

            $this->transactionService->handleWithLogDB(
                'update-persetujuan',
                'persetujuan',
                $id_persetujuan,
                json_encode($persetujuan)
            );

            return response()->json([
                'success' => true,
                'message' => 'Data persetujuan berhasil diperbarui!',
                'data' => $persetujuan
            ], 200);
        }, 'Update Persetujuan');
    }

    public function destroy($id_persetujuan)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_persetujuan) {
            $persetujuan = Persetujuan::find($id_persetujuan);

            if (!$persetujuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data persetujuan tidak ditemukan'
                ], 404);
            }

            $this->transactionService->handleWithLogDB(
                'delete-persetujuan',
                'persetujuan',
                $id_persetujuan,
                json_encode($persetujuan)
            );

            $persetujuan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data persetujuan berhasil dihapus!'
            ], 200);
        }, 'Delete Persetujuan');
    }
}
