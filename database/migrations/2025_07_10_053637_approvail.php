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
        Schema::create('approvail', function (Blueprint $table){
            $table->id('id_approvail');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran')->onDelete('cascade');
            $table->unsignedBigInteger('id_verifikator');
            $table->foreign('id_verifikator')->references('id_verifikator')->on('verifikator')->onDelete('cascade');
            $table->enum('status', ['Setujui', 'Tolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvail');
    }
};
