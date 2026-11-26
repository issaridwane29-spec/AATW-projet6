<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QrPlatformSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('utilisateur')->insert([
            ['nom' => 'Admin AATW', 'email' => 'admin@aatw.com',   'mot_de_passe' => Hash::make('password'), 'role' => 'ADMIN'],
            ['nom' => 'Aurlane',    'email' => 'aurlane@aatw.com', 'mot_de_passe' => Hash::make('password'), 'role' => 'UTILISATEUR'],
            ['nom' => 'Divin',      'email' => 'divin@aatw.com',   'mot_de_passe' => Hash::make('password'), 'role' => 'UTILISATEUR'],
        ]);

        DB::table('personnalisation')->insert([
            ['forme' => 'arrondi', 'couleur_fond' => '#FFFFFF', 'couleur_points' => '#1A73E8', 'cadre' => 'simple'],
            ['forme' => 'rond',    'couleur_fond' => '#FFF8E1', 'couleur_points' => '#E65100', 'cadre' => null],
            ['forme' => 'carre',   'couleur_fond' => '#FFFFFF', 'couleur_points' => '#000000', 'cadre' => null],
        ]);

        DB::table('qr_codes')->insert([
            ['titre' => 'Site vitrine',    'contenu' => 'https://exemple.com',          'type' => 'URL',  'code_court' => 'abc123', 'nb_scans_max' => 100, 'id_user' => 2, 'id_perso' => 1],
            ['titre' => 'Menu restaurant', 'contenu' => 'https://exemple.com/menu.pdf', 'type' => 'URL',  'code_court' => 'menu01', 'nb_scans_max' => 0,   'id_user' => 2, 'id_perso' => 2],
            ['titre' => 'Wifi bureau',     'contenu' => 'WIFI:T:WPA;S:Bureau;P:1234;;', 'type' => 'WIFI', 'code_court' => 'wifi01', 'nb_scans_max' => 3,   'id_user' => 3, 'id_perso' => 3],
        ]);

        $now = now();
        $scans = [
            [1, $now->copy()->subDays(3),  '41.77.10.5', 'Congo (RDC)', 'Kinshasa',   false],
            [1, $now->copy()->subDays(2),  '41.77.10.9', 'Congo (RDC)', 'Kinshasa',   false],
            [1, $now->copy()->subDay(),    '82.64.20.1', 'France',      'Paris',      false],
            [2, $now->copy()->subHours(5), '105.12.4.7', 'Cameroun',    'Douala',     false],
            [3, $now->copy()->subHours(4), '154.72.1.3', 'Gabon',       'Libreville', false],
            [3, $now->copy()->subHours(3), '154.72.1.4', 'Gabon',       'Libreville', false],
            [3, $now->copy()->subHours(2), '154.72.1.5', 'Gabon',       'Libreville', false],
            [3, $now->copy()->subHours(1), '154.72.1.6', 'Gabon',       'Libreville', true],  // bloqué : quota atteint
        ];

        // Insertion une par une pour déclencher le trigger du quota
        foreach ($scans as [$qr, $date, $ip, $country, $city, $blocked]) {
            DB::table('scans')->insert([
                'qr_id' => $qr, 'scanned_at' => $date, 'ip_address' => $ip,
                'country' => $country, 'city' => $city, 'bloque' => $blocked,
            ]);
        }
    }
}
