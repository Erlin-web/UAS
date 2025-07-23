<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table){
           $table->id('id_pendaftaran'); // Sudah otomatis primary key
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('id_beasiswa');
            $table->foreign('id_beasiswa')->references('id_beasiswa')->on('beasiswa')->onDelete('cascade');
            $table->string('kode');
            $table->foreign('kode')->references('kode')->on('list_universitas')->onDelete('cascade');
            $table->string('telp');
            $table->text('alamat');
            $table->enum('status', ['Proses', 'Setujui', 'Tolak'])->default('Proses');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};