<?php

namespace App\Http\Controllers;

use App\Mail\OTP;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }


    public function logout(Request $request)
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $user = $request->user();

            $user->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil logout. Token dihapus.'
            ], 200);
        }, 'Logout');
    }
}
