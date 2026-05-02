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
        Schema::create('photo_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->string('email');
            $table->string('kode_download')->unique();
            $table->string('link_file_foto')->nullable();
            $table->string('link_file_video')->nullable();
            $table->timestamp('waktu_mulai')->useCurrent();
            $table->timestamp('waktu_selesai')->nullable();
            $table->enum('status_cetak', ['pending', 'printing', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_sessions');
    }
};
