<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class PwaManifestController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'name' => config('app.name'),
            'short_name' => Setting::get('pwa_short_name', config('app.name')),
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => Setting::get('pwa_theme_color', '#4f46e5'),
            'icons' => [
                ['src' => '/images/icons/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png'],
                ['src' => '/images/icons/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png'],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json']);
    }
}
