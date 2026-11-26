<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 150)->default('Sans titre');
            $table->text('contenu');
            $table->enum('type', ['URL', 'TEXTE', 'VCARD', 'WIFI', 'EMAIL'])->default('URL');
            $table->string('code_court', 20)->unique();
            $table->unsignedInteger('nb_scans_max')->default(0);     // 0 = illimité
            $table->unsignedInteger('nb_scans_actuel')->default(0);
            $table->enum('statut', ['ACTIF', 'INACTIF', 'EXPIRE'])->default('ACTIF');
            $table->dateTime('date_creation')->useCurrent();
            $table->dateTime('date_expiration')->nullable();

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_perso')->unique();

            $table->foreign('id_user')->references('id_user')->on('utilisateur')->cascadeOnDelete();
            $table->foreign('id_perso')->references('id_perso')->on('personnalisation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
