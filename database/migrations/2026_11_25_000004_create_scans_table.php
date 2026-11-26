<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qr_id');
            $table->dateTime('scanned_at')->useCurrent();
            $table->string('ip_address', 45);
            $table->string('country', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->boolean('bloque')->default(false);   // true = scan refusé (quota atteint)

            $table->foreign('qr_id')->references('id')->on('qr_codes')->cascadeOnDelete();

            $table->index('scanned_at', 'idx_scans_scanned_at');
            $table->index(['country', 'city'], 'idx_scans_zone');
            $table->index('ip_address', 'idx_scans_ip');
            $table->index(['qr_id', 'scanned_at'], 'idx_scans_qr_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
