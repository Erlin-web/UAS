<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        return $this->transactionService->handleWithTransaction(function () {
            $beasiswa = Beasiswa::all();

            return response()->json([
                'success' => true,
                'message' => 'List semua beasiswa',
                'data' => $beasiswa
            ]);
        }, 'List Beasiswa');
    }

    public function show($id_beasiswa)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_beasiswa) {
            $beasiswa = Beasiswa::find($id_beasiswa);

            if (!$beasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail beasiswa',
                'data' => $beasiswa
            ]);
        }, 'Show Beasiswa');
    }

    public function store(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $validated = $request->validate([
                'nama_beasiswa' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'persyaratan' => 'required|string',
            ]);

            $beasiswa = Beasiswa::create($validated);

            $this->transactionService->handleWithLogDB(
                'store-beasiswa',
                'beasiswa',
                $beasiswa->id_beasiswa,
                json_encode($beasiswa)
            );

            return response()->json([
                'success' => true,
                'message' => 'Beasiswa berhasil ditambahkan.',
                'data' => $beasiswa
            ], 201);
        }, 'Store Beasiswa');
    }

    public function update(Request $request, $id_beasiswa)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request, $id_beasiswa) {
            $validated = $request->validate([
                'nama_beasiswa' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'persyaratan' => 'required|string',
            ]);

            $beasiswa = Beasiswa::find($id_beasiswa);
            if (!$beasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $beasiswa->update($validated);

            $this->transactionService->handleWithLogDB(
                'update-beasiswa',
                'beasiswa',
                $id_beasiswa,
                json_encode($beasiswa)
            );

            return response()->json([
                'success' => true,
                'message' => 'Beasiswa berhasil diupdate.',
                'data' => $beasiswa
            ]);
        }, 'Update Beasiswa');
    }

    public function destroy($id_beasiswa)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_beasiswa) {
            $beasiswa = Beasiswa::find($id_beasiswa);
            if (!$beasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $this->transactionService->handleWithLogDB(
                'delete-beasiswa',
                'beasiswa',
                $id_beasiswa,
                json_encode($beasiswa)
            );

            $beasiswa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Beasiswa berhasil dihapus.'
            ]);
        }, 'Delete Beasiswa');
    }
}
