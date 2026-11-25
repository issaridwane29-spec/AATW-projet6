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
    Schema::table('qr_codes', function (Blueprint $table) {
        $table->unsignedInteger('max_scans')->nullable();
        $table->unsignedInteger('nb_scans')->default(0);
    });
}

public function down(): void
{
    Schema::table('qr_codes', function (Blueprint $table) {
        $table->dropColumn(['max_scans', 'nb_scans']);
    });
}

    
};
