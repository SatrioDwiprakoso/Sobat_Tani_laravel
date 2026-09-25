<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $artikels
        ]);
    }
}
