<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScanController extends Controller
{
    public function scanner(Request $request, $id)
    {
        $qr = QrCode::findOrFail($id);

        if ($qr->max_scans !== null && $qr->nb_scans >= $qr->max_scans) {
            abort(403, 'Limite de scans atteinte');
        }

        $ip = $request->ip();
        $pays = null;
        $ville = null;

        try {
            $r = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
            if ($r->json('status') === 'success') {
                $pays = $r->json('country');
                $ville = $r->json('city');
            }
        } catch (\Throwable $e) {
            // la localisation ne doit pas bloquer le scan
        }

        Scan::create([
            'qr_code_id' => $qr->id,
            'ip' => $ip,
            'pays' => $pays,
            'ville' => $ville,
        ]);
        $qr->increment('nb_scans');

        return redirect()->away($qr->destination_url);
    }

    public function historique(Request $request, $id)
    {
        $q = Scan::where('qr_code_id', $id);

        if ($request->filled('from')) $q->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))   $q->whereDate('created_at', '<=', $request->to);
        if ($request->filled('pays')) $q->where('pays', $request->pays);
        if ($request->filled('ip'))   $q->where('ip', $request->ip);

        return response()->json(
            $q->orderByDesc('created_at')->get(['ip', 'pays', 'ville', 'created_at'])
        );
    }

    public function majLimite(Request $request, $id)
    {
        $data = $request->validate(['max' => 'nullable|integer|min:0']);

        $qr = QrCode::findOrFail($id);
        $qr->max_scans = $data['max'] ?? null;
        $qr->save();

        return response()->json($qr->only(['id', 'max_scans', 'nb_scans']));
    }
}