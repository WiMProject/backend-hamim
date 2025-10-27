<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

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
        
        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imageSize = getimagesize($file->getPathname());
            if ($imageSize) {
                $metadata['width'] = $imageSize[0];
                $metadata['height'] = $imageSize[1];
            }
        }
        
        return $metadata;
    }
}