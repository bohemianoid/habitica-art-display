<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;

class GenerateWebAppManifest extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'name' => config('app.name'),
            'short_name' => config('web.short_name'),
            'display' => 'standalone',
            'theme_color' => '#111827',
            'icons' => [
                [
                    'src' => Vite::asset('resources/images/icon256.webp'),
                    'size' => '1024x1024',
                    'type' => 'image/webp',
                ],
            ],
        ]);
    }
}
