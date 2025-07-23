<?php

use App\Http\Controllers\ApprovailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ListSekolahController;
use App\Http\Controllers\ListUniversitasController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MailerController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [RegisterController::class, 'login']);
Route::post('/register', [RegisterController::class,'register']);
Route::post('/verifikasi-otp', [MailerController::class, 'verifikasi']);
Route::post('/otp/resend', [MailerController::class, 'resendOtp']);

Route::prefix('forgot-password')->group(function () {
    Route::post('/send', [ForgotPasswordController::class, 'send']);
    Route::post('/verifikasi', [ForgotPasswordController::class, 'verifikasi']);
    Route::post('/reset', [ForgotPasswordController::class, 'resetPassword']);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('role');

        return response()->json([
            'data' => [
                'id_user' => $user->id_user,
                'username' => $user->username, // inilah yang akan dibaca oleh JavaScript
                'email' => $user->email,
                'role' => $user->role->nama_role
            ]
        ]);
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id_role}', [RoleController::class, 'show']);
        Route::put('/{id_role}', [RoleController::class, 'update']);
        Route::delete('/{id_role}', [RoleController::class, 'destroy']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id_user}', [UserController::class, 'show']);
        Route::put('/{id_user}', [UserController::class, 'update']);
        Route::delete('/{id_user}', [UserController::class, 'destroy']); 
    });

    
    Route::prefix('verifikator')->middleware(['role:admin'])->group(function () {
        Route::get('/', [VerifikatorController::class, 'index']);
        Route::post('/', [VerifikatorController::class, 'store']);
        Route::get('/{id_verifikator}', [VerifikatorController::class, 'show']);
        Route::put('/{id_verifikator}', [VerifikatorController::class, 'update']);
        Route::delete('/{id_verifikator}', [VerifikatorController::class, 'destroy']);
    });

    Route::prefix('beasiswa')->group(function () {
        Route::get('/', [BeasiswaController::class, 'index']);
        Route::middleware('role:peserta,admin,verifikator')->group(function(){
            Route::get('/{id_beasiswa}', [BeasiswaController::class, 'show']);
        });
        
        Route::middleware('role:admin')->group(function(){
            Route::post('/', [BeasiswaController::class, 'store']);
            Route::put('/{id_beasiswa}', [BeasiswaController::class, 'update']);
            Route::delete('/{id_beasiswa}', [BeasiswaController::class, 'destroy']);
        });
    });

    Route::prefix('pendaftaran')->group(function () {
        Route::get('/', [PendaftaranController::class, 'index']);
        Route::get('/{id_pendaftaran}', [PendaftaranController::class, 'show']);

        Route::middleware('role:admin,peserta')->group(function(){
            Route::post('/', [PendaftaranController::class, 'store']);
            Route::put('/{id_pendaftaran}', [PendaftaranController::class, 'update']);
        });
        Route::middleware('role:peserta,admin,verifikator')->group(function(){
            Route::delete('/{id_pendaftaran}', [PendaftaranController::class, 'destroy']);
        });
    });

    Route::prefix('persetujuan')->group(function () {
        Route::get('/', [PersetujuanController::class, 'index']);
        Route::get('/{id_persetujuan}', [PersetujuanController::class, 'show']);

        // Route::middleware('role:admin')->group(function(){
        //     Route::post('/', [PersetujuanController::class, 'store']);
        //     Route::put('/{id_persetujuan}', [PersetujuanController::class, 'update']);
        //     Route::delete('/{id_persetujuan}', [PersetujuanController::class, 'destroy']);
        // });
    });

    Route::prefix('list_universitas')->group(function () {
        Route::get('/', [ListUniversitasController::class, 'index']);
        Route::post('/', [ListUniversitasController::class, 'store']);
        Route::get('/{kode}', [ListUniversitasController::class, 'show']);
        Route::put('/{kode}', [ListUniversitasController::class, 'update']);
        Route::delete('/{kode}', [ListUniversitasController::class, 'destroy']); 
    });

   Route::prefix('dokumen')->group(function () {
        Route::get('/', [DokumenController::class, 'index']);
        Route::get('/{id_dokumen}', [DokumenController::class, 'show']);
        Route::put('/update-by-pendaftaran/{id_pendaftaran}', [DokumenController::class, 'updateByPendaftaran']);

        Route::middleware('role:admin,peserta')->group(function () {
            Route::put('/{id_dokumen}', [DokumenController::class, 'update']);
            Route::post('/', [DokumenController::class, 'store']);
            Route::delete('/{id_dokumen}', [DokumenController::class, 'destroy']);
        });
    });

    Route::prefix('approvail')->middleware('role:verifikator')->group(function () {
    Route::get('/', [ApprovailController::class, 'index']);
    Route::post('/{id_pendaftaran}/approve', [ApprovailController::class, 'approve']);
});

});


Route::middleware(['auth:sanctum', 'role:verifikator'])->prefix('verifikator/pendaftaran')->group(function () {
    Route::post('{id_pendaftaran}/approve', [ApprovailController::class, 'approve']);
});


Route::middleware(['auth:sanctum', 'role:admin'])->prefix('logs')->group(function () {
    Route::get('/activity', [LogController::class, 'activity']);
    Route::get('/error', [LogController::class, 'error']);
    Route::get('/database', [LogController::class, 'database']);
});
