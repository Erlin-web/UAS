<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    // Menampilkan semua user
    public function index(Request $request)
    {
        $role = $request->query('role');

        $user = User::with('role')
            ->when($role, function ($query, $role) {
                $query->whereHas('role', function ($q) use ($role) {
                    $q->where('nama_role', $role);
                });
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }


    // Menyimpan user baru
    public function store(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $validated = $request->validate([
                'id_role' => 'required|exists:role,id_role',
                'username' => 'required|string|max:100',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|string|min:6'
            ]);

            $validated['password'] = bcrypt($validated['password']);

            $user = User::create($validated);

            $this->transactionService->handleWithLogDB(
                'store-user',
                'user',
                $user->id_user,
                json_encode($user)
            );

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan.',
                'data' => $user
            ], 201);
        }, 'Store User');
    }

    // Menampilkan detail user
    public function show($id_user)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_user) {
            $user = User::with('role')->find($id_user);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail user',
                'data' => $user
            ]);
        }, 'Show User');
    }

    // Mengupdate user
   public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'id_role' => 'required|exists:role,id_role',
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:user,email,' . $id . ',id_user',
                'password' => 'nullable|string|min:6',
            ]);

            $user = \App\Models\User::findOrFail($id);
            $user->id_role = $validated['id_role'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            
            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada basis data. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    // Menghapus user
    public function destroy($id_user)
    {
        return $this->transactionService->handleWithTransaction(function () use ($id_user) {
            $user = User::find($id_user);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            $this->transactionService->handleWithLogDB(
                'delete-user',
                'user',
                $user->id_user,
                json_encode($user)
            );

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        }, 'Delete User');
    }
}
