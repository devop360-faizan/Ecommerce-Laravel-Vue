<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Categories
        $categories = ['Smartphones', 'Laptops', 'Audio', 'Accessories'];
        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[] = DB::table('categories')->insertGetId([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Create Brands
        $brands = ['Apple', 'Samsung', 'Sony', 'Logitech'];
        $brandIds = [];
        foreach ($brands as $brand) {
            $brandIds[] = DB::table('brands')->insertGetId([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Create Products
        for ($i = 1; $i <= 12; $i++) {
            $catId = $categoryIds[array_rand($categoryIds)];
            $brandId = $brandIds[array_rand($brandIds)];
            
            $productId = DB::table('products')->insertGetId([
                'category_id' => $catId,
                'brand_id' => $brandId,
                'name' => 'Premium Product ' . $i,
                'slug' => 'premium-product-' . $i,
                'sku' => 'PRD-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                'short_description' => 'A great premium product.',
                'description' => 'This is a longer description for premium product ' . $i . '. It features amazing capabilities and top-tier build quality.',
                'price' => rand(99, 1999) + 0.99,
                'stock' => rand(10, 100),
                'is_active' => true,
                'is_featured' => rand(0, 1) === 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('product_images')->insert([
                'product_id' => $productId,
                'image_path' => 'https://picsum.photos/seed/prd' . $i . '/600/800',
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
