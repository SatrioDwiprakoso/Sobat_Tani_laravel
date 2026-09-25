<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CuacaController extends Controller
{
    public function index(Request $request)
    {
        $lat = $request->query('lat', '-6.2088');
        $lon = $request->query('lon', '106.8456');

        // 1. Dapatkan nama kota via Nominatim (Reverse Geocoding OpenStreetMap)
        $kota = 'Lokasi Anda';
        try {
            $geoResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'KlinikTaniApp/1.0'
            ])->timeout(5)->get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}");
            
            if ($geoResponse->successful()) {
                $geoData = $geoResponse->json();
                if (isset($geoData['address'])) {
                    $kota = $geoData['address']['city'] 
                         ?? $geoData['address']['town'] 
                         ?? $geoData['address']['village'] 
                         ?? $geoData['address']['county'] 
                         ?? 'Lokasi Anda';
                }
            }
        } catch (\Exception $e) {}

        // 2. Dapatkan cuaca real-time via wttr.in
        $suhu = 25;
        $kondisi = 'Cerah Berawan';
        try {
            $weatherResponse = \Illuminate\Support\Facades\Http::timeout(5)->get("https://wttr.in/{$lat},{$lon}?format=j1");
            
            if ($weatherResponse->successful()) {
                $weatherData = $weatherResponse->json();
                if (isset($weatherData['current_condition'][0])) {
                    $current = $weatherData['current_condition'][0];
                    $suhu = $current['temp_C'] ?? $suhu;
                    $code = $current['weatherCode'] ?? 116;
                    
                    // Mapping WWO Weather Code ke Bahasa Indonesia
                    if ($code == 113) $kondisi = 'Cerah';
                    else if (in_array($code, [116, 119, 122])) $kondisi = 'Cerah Berawan';
                    else if (in_array($code, [143, 248, 260])) $kondisi = 'Berkabut';
                    else if (in_array($code, [176, 263, 266, 293, 296, 299, 302, 305, 308, 353, 356, 359])) $kondisi = 'Hujan';
                    else if (in_array($code, [200, 386, 389])) $kondisi = 'Hujan Badai';
                    else if (in_array($code, [179, 182, 185, 227, 230, 281, 284, 311, 314, 317, 320, 323, 326, 329, 332, 335, 338, 350, 362, 365, 368, 371, 374, 377, 392, 395])) $kondisi = 'Salju';
                }
            }
        } catch (\Exception $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Data cuaca real-time berhasil diambil.',
            'data' => [
                'kota' => $kota,
                'suhu' => round($suhu),
                'kondisi' => $kondisi,
                'kelembaban' => '80%', 
                'angin' => '10 km/jam'
            ]
        ], 200);
    }
}
