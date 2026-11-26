<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateur', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nom', 100);
            $table->string('email', 150)->unique();
            $table->string('mot_de_passe', 255);
            $table->enum('role', ['UTILISATEUR', 'ADMIN'])->default('UTILISATEUR');
            $table->dateTime('date_inscription')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateur');
    }
};
