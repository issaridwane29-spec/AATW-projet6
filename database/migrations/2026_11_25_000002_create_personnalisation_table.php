<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnalisation', function (Blueprint $table) {
            $table->id('id_perso');
            $table->string('forme', 50)->default('carre');
            $table->string('couleur_fond', 7)->default('#FFFFFF');
            $table->string('couleur_points', 7)->default('#000000');
            $table->string('logo', 255)->nullable();
            $table->string('cadre', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnalisation');
    }
};
