<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('admin.dashboard');
// });

Route::get('/', function () {
    return view('tampilan.utama');
});
Route::view('/login', 'user.login')->name('login');
Route::view('/register', 'user.register')->name('register');
Route::view('/otp', 'user.otp')->name('otp');

Route::view('/forgot-password', 'user.forgot-password')->name('forgot-password');
Route::view('/verifikasi-otp', 'user.verifikasi-otp')->name('verifikasi-otp');
Route::view('/reset-password', 'user.reset-password')->name('reset-password');





Route::get('/admin/dashboard', fn() => view('admin.index'))->name('admin.dashboard.index');

Route::prefix('admin/verifikator')->group(function () {
    Route::get('/',fn() => view('admin.verifikator.index'))->name('admin.verifikator.index');
    Route::get('create',fn() => view('admin.verifikator.create'))->name('admin.verifikator.create');
    Route::get('/{id_verifikator}/edit',fn() => view('admin.verifikator.edit'))->name('admin.verifikator.edit');
});

Route::prefix('admin/beasiswa')->group(function () {
    Route::get('/',fn() => view('admin.beasiswa.index'))->name('admin.beasiswa.index');
    Route::get('create',fn() => view('admin.beasiswa.create'))->name('admin.beasiswa.create');
    Route::get('/{id_beasiswa}/edit',fn() => view('admin.beasiswa.edit'))->name('admin.beasiswa.edit');
});

Route::prefix('admin/user')->group(function () {
    Route::get('/',fn() => view('admin.user.index'))->name('admin.user.index');
    Route::get('create',fn() => view('admin.user.create'))->name('admin.user.create');
    Route::get('/{id_user}/edit',fn() => view('admin.user.edit'))->name('admin.user.edit');
});

Route::prefix('admin/pendaftaran')->group(function () {
    Route::get('/',fn() => view('admin.pendaftaran.index'))->name('admin.pendaftaran.index');
    Route::get('create',fn() => view('admin.pendaftaran.create'))->name('admin.pendaftaran.create');
    Route::get('/{id_pendaftaran}/edit',fn() => view('admin.pendaftaran.edit'))->name('admin.pendaftaran.edit');
    Route::get('/{id_pendaftaran}/show',fn() => view('admin.pendaftaran.show'))->name('admin.pendaftaran.show');
});

Route::prefix('admin/persetujuan')->group(function () {
    Route::get('/',fn() => view('admin.persetujuan.index'))->name('admin.persetujuan.index');
    Route::get('create',fn() => view('admin.persetujuan.create'))->name('admin.persetujuan.create');
    Route::get('/{id_persetujuan}/edit',fn() => view('admin.persetujuan.edit'))->name('admin.persetujuan.edit');
    Route::get('/{id_persetujuan}/show',fn() => view('admin.persetujuan.show'))->name('admin.persetujuan.show');
});

Route::prefix('admin/list_universitas')->group(function () {
    Route::get('/',fn() => view('admin.list_universitas.index'))->name('admin.list_universitas.index');
    Route::get('create',fn() => view('admin.list_universitas.create'))->name('admin.list_universitas.create');
    Route::get('/{kode}/edit',fn() => view('admin.list_universitas.edit'))->name('admin.list_universitas.edit');
});

Route::prefix('admin/role')->group(function () {
    Route::get('/',fn() => view('admin.role.index'))->name('admin.role.index');
    Route::get('create',fn() => view('admin.role.create'))->name('admin.role.create');
    Route::get('/{id_role}/edit',fn() => view('admin.role.edit'))->name('admin.role.edit');
});

Route::prefix('admin/logs')->name('logs.')->group(function () {
    Route::view('/logactivity', 'admin.logs.logactivity')->name('logactivity');
    Route::view('/logerror', 'admin.logs.logerror')->name('logerror');
    Route::view('/logdatabase', 'admin.logs.logdatabase')->name('logdatabase');
});

//verifikator
Route::prefix('verifikator')->name('verifikator.')->group(function () {
    Route::view('/dashboard', 'verifikator.index')->name('dashboard');

    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::view('/', 'verifikator.pendaftaran.index')->name('index');
        Route::get('/{id_verifikator}/show',fn() => view('verifikator.pendaftaran.show'))->name('pendaftaran.show');
    });
});



//peserta
Route::get('/peserta/dashboard', fn() => view('peserta.index'))->name('peserta.dashboard.index');

Route::prefix('peserta/beasiswa')->group(function () {
    Route::get('/',fn() => view('peserta.beasiswa.index'))->name('peserta.beasiswa.index');
    Route::get('/{id_beasiswa}/show',fn() => view('peserta.beasiswa.show'))->name('peserta.beasiswa.show');
});

Route::prefix('peserta/pendaftaran')->group(function () {
    Route::get('/',fn() => view('peserta.pendaftaran.index'))->name('peserta.pendaftaran.index');
    Route::get('create',fn() => view('peserta.pendaftaran.create'))->name('peserta.pendaftaran.create');
    Route::get('/{id_pendaftaran}/edit',fn() => view('peserta.pendaftaran.edit'))->name('peserta.pendaftaran.edit');
    Route::get('/{id_pendaftaran}/show',fn() => view('peserta.pendaftaran.show'))->name('peserta.pendaftaran.show');
    
});

Route::prefix('peserta/persetujuan')->group(function () {
    Route::get('/',fn() => view('peserta.persetujuan.index'))->name('peserta.persetujuan.index');
    Route::get('create',fn() => view('peserta.persetujuan.create'))->name('peserta.persetujuan.create');
    Route::get('/{id_persetujuan}/edit',fn() => view('peserta.persetujuan.edit'))->name('peserta.persetujuan.edit');
});