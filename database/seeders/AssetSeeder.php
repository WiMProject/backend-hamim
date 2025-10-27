<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            [
                'name' => 'Banner Homepage',
                'type' => 'image',
                'category' => 'banner',
                'file_path' => 'assets/banner-homepage.jpg',
                'file_url' => 'https://picsum.photos/1920/1080?random=1',
                'file_size' => 245760,
                'mime_type' => 'image/jpeg',
                'metadata' => ['width' => 1920, 'height' => 1080, 'alt_text' => 'Homepage Banner'],
                'is_active' => true
            ],
            [
                'name' => 'Banner Promo',
                'type' => 'image',
                'category' => 'banner',
                'file_path' => 'assets/banner-promo.jpg',
                'file_url' => 'https://picsum.photos/1920/600?random=2',
                'file_size' => 180000,
                'mime_type' => 'image/jpeg',
                'metadata' => ['width' => 1920, 'height' => 600, 'alt_text' => 'Promo Banner'],
                'is_active' => true
            ],
            [
                'name' => 'Product Image 1',
                'type' => 'image',
                'category' => 'product',
                'file_path' => 'assets/product-1.jpg',
                'file_url' => 'https://picsum.photos/800/800?random=3',
                'file_size' => 120000,
                'mime_type' => 'image/jpeg',
                'metadata' => ['width' => 800, 'height' => 800, 'alt_text' => 'Product 1'],
                'is_active' => true
            ],
            [
                'name' => 'Logo Company',
                'type' => 'image',
                'category' => 'logo',
                'file_path' => 'assets/logo.png',
                'file_url' => 'https://via.placeholder.com/300x100/4F46E5/FFFFFF?text=LOGO',
                'file_size' => 15000,
                'mime_type' => 'image/png',
                'metadata' => ['width' => 300, 'height' => 100, 'alt_text' => 'Company Logo'],
                'is_active' => true
            ]
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}