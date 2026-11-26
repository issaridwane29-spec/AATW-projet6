<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Incrémente le compteur à chaque scan accepté et passe le QR en EXPIRE à la limite.
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_scan_apres_insert');
        DB::unprepared("
            CREATE TRIGGER trg_scan_apres_insert
            AFTER INSERT ON scans
            FOR EACH ROW
            BEGIN
                IF NEW.bloque = 0 THEN
                    UPDATE qr_codes
                    SET nb_scans_actuel = nb_scans_actuel + 1,
                        statut = CASE
                            WHEN nb_scans_max > 0 AND nb_scans_actuel + 1 >= nb_scans_max THEN 'EXPIRE'
                            ELSE statut
                        END
                    WHERE id = NEW.qr_id;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_scan_apres_insert');
    }
};
