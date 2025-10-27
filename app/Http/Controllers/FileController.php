<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function serve($path)
    {
        $fullPath = 'assets/' . $path;
        
        if (!Storage::disk('public')->exists($fullPath)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        
        $file = Storage::disk('public')->get($fullPath);
        $mimeType = Storage::disk('public')->mimeType($fullPath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=31536000');
    }
}