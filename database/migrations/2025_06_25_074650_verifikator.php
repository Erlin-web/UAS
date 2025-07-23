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
        Schema::create('verifikator', function (Blueprint $table) {
            $table->id('id_verifikator');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            $table->tinyInteger('tahapan');
            $table->string('jabatan', 100);
            $table->enum('status', ['aktif', 'nonaktif', ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikator');
    }
};