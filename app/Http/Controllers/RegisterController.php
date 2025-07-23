<?php

namespace App\Http\Controllers;

use App\Mail\OTP;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    
    public function register(Request $request)
    {
        // Validasi input (id_role tidak perlu divalidasi)
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Tetapkan id_role default = 3
        $defaultRole = 3;

        // Generate OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan user
        $user = User::create([
            'id_role' => $defaultRole,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Kirim Email OTP
        try {
            Mail::to($user->email)->send(new OTP($otp));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi berhasil, tapi gagal mengirim OTP.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. OTP telah dikirim ke email Anda.',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $user = User::with('role')->where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah.',
                ], 401);
            }

            // Ambil role user (misal: admin, peserta, verifikator)
            $role = $user->role->nama_role ?? null;

            // Peserta wajib verifikasi email
            if ($role === 'peserta' && is_null($user->email_verified_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email belum diverifikasi. Silakan cek email Anda dan verifikasi OTP.',
                ], 403);
            }

            // Buat token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Set expired token 6 jam
            $user->tokens()->latest()->first()->update([
                'expires_at' => now()->addHours(6)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil login.',
                'data' => [
                    'token' => 'Bearer ' . $token,
                    'user' => $user
                ]
            ], 200);
        }, 'Login');
    }


}
