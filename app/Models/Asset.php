<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name', 'type', 'category', 'file_path', 'file_url', 
        'file_size', 'mime_type', 'metadata', 'is_active'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean'
    ];
}