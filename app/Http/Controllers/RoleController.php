<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    // Menampilkan semua role
    public function index()
    {
        return $this->transactionService->handleWithTransaction(function () {
            $role = Role::with('user')->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar semua role beserta user',
                'data' => $role
            ]);
        }, 'List Role');
    }

    // Menyimpan role baru
    public function store(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $request->validate([
                'nama_role' => 'required|string|max:50'
            ]);

            $role = Role::create([
                'nama_role' => $request->nama_role
            ]);

            $this->transactionService->handleWithLogDB(
                'store-role',
                'role',
                $role->id_role,
                json_encode($role)
            );

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan',
                'data' => $role
            ], 201);
        }, 'Store Role');
    }

    // Menampilkan satu role
    public function show($id_role)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_role) {
            $role = Role::with('user')->find($id_role);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail role',
                'data' => $role
            ]);
        }, 'Show Role');
    }

    public function update(Request $request, $id_role)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request, $id_role) {
            $request->validate([
                'nama_role' => 'required|string|max:50'
            ]);

            $role = Role::find($id_role);
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan.'
                ], 404);
            }

            $role->update([
                'nama_role' => $request->nama_role
            ]);

            $this->transactionService->handleWithLogDB(
                'update-role',
                'role',
                $role->id_role,
                json_encode($role)
            );

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui.',
                'data' => $role
            ]);
        }, 'Update Role');
    }

    public function destroy($id_role)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_role) {
            $role = Role::find($id_role);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }

            $this->transactionService->handleWithLogDB(
                'delete-role',
                'role',
                $role->id_role,
                json_encode($role)
            );

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus'
            ]);
        }, 'Delete Role');
    }
}
