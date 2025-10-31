<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::where('is_active', true);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $assets = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'message' => 'Assets retrieved successfully',
            'data' => $assets
        ]);
    }

    public function show($id)
    {
        $asset = Asset::where('is_active', true)->find($id);
        
        if (!$asset) {
            return response()->json(['message' => 'Asset not found'], 404);
        }
        
        return response()->json([
            'message' => 'Asset retrieved successfully',
            'data' => $asset
        ]);
    }

    public function getTranslations(Request $request)
    {
        $query = Asset::where('type', 'translation')->where('is_active', true);
        
        if ($request->has('language')) {
            $query->whereJsonContains('metadata->language', $request->language);
        }
        
        $translations = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'message' => 'Translations retrieved successfully',
            'data' => $translations
        ]);
    }

    public function getTranslationContent($language)
    {
        $translation = Asset::where('type', 'translation')
            ->where('is_active', true)
            ->whereJsonContains('metadata->language', $language)
            ->latest()
            ->first();
        
        if (!$translation) {
            return response()->json(['message' => 'Translation not found'], 404);
        }
        
        $content = Storage::disk('public')->get($translation->file_path);
        $translations = json_decode($content, true);
        
        return response()->json([
            'message' => 'Translation content retrieved successfully',
            'language' => $language,
            'data' => $translations
        ]);
    }

    public function createTranslation(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'language' => 'required|string|max:10', // id, en, ar
            'translations' => 'required|array'
        ]);

        // Convert array to JSON string
        $jsonContent = json_encode($request->translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // Save as file
        $filename = $request->language . '_' . time() . '.json';
        $path = 'assets/' . $filename;
        
        Storage::disk('public')->put($path, $jsonContent);
        
        $asset = Asset::create([
            'name' => $request->name,
            'type' => 'translation',
            'category' => 'language',
            'file_path' => $path,
            'file_url' => url('storage/' . $path),
            'file_size' => strlen($jsonContent),
            'mime_type' => 'application/json',
            'metadata' => [
                'format' => 'translation',
                'language' => $request->language,
                'keys_count' => count($request->translations)
            ]
        ]);

        return response()->json([
            'message' => 'Translation created successfully',
            'data' => $asset
        ], 201);
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|max:10240', // 10MB max
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'category' => 'nullable|string|max:100'
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('assets', $filename, 'public');
        
        $asset = Asset::create([
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'file_path' => $path,
            'file_url' => url('storage/' . $path),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'metadata' => $this->getFileMetadata($file)
        ]);

        return response()->json([
            'message' => 'Asset uploaded successfully',
            'data' => $asset
        ], 201);
    }

    private function getFileMetadata($file)
    {
        $metadata = [];
        $mimeType = $file->getMimeType();
        
        // Image metadata
        if (str_starts_with($mimeType, 'image/')) {
            $imageSize = getimagesize($file->getPathname());
            if ($imageSize) {
                $metadata['width'] = $imageSize[0];
                $metadata['height'] = $imageSize[1];
            }
        }
        
        // Lottie JSON metadata
        if ($mimeType === 'application/json') {
            $content = file_get_contents($file->getPathname());
            $json = json_decode($content, true);
            if ($json && isset($json['w'], $json['h'])) {
                $metadata['width'] = $json['w'];
                $metadata['height'] = $json['h'];
                $metadata['format'] = 'lottie';
                if (isset($json['fr'])) $metadata['frame_rate'] = $json['fr'];
                if (isset($json['op'])) $metadata['frames'] = $json['op'];
            }
        }
        
        // Audio/Video metadata with getID3
        if (str_starts_with($mimeType, 'audio/') || str_starts_with($mimeType, 'video/')) {
            try {
                $getID3 = new \getID3();
                $fileInfo = $getID3->analyze($file->getPathname());
                
                if (isset($fileInfo['playtime_seconds'])) {
                    $metadata['duration'] = round($fileInfo['playtime_seconds'], 2);
                }
                
                if (str_starts_with($mimeType, 'audio/')) {
                    $metadata['format'] = 'audio';
                    if (isset($fileInfo['audio']['bitrate'])) {
                        $metadata['bitrate'] = $fileInfo['audio']['bitrate'];
                    }
                    if (isset($fileInfo['audio']['sample_rate'])) {
                        $metadata['sample_rate'] = $fileInfo['audio']['sample_rate'];
                    }
                }
                
                if (str_starts_with($mimeType, 'video/')) {
                    $metadata['format'] = 'video';
                    if (isset($fileInfo['video']['resolution_x'], $fileInfo['video']['resolution_y'])) {
                        $metadata['width'] = $fileInfo['video']['resolution_x'];
                        $metadata['height'] = $fileInfo['video']['resolution_y'];
                    }
                }
            } catch (\Exception $e) {
                $metadata['format'] = str_starts_with($mimeType, 'audio/') ? 'audio' : 'video';
            }
        }
        
        return $metadata;
    }
}