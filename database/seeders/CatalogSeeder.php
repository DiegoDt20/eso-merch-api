<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    /**
     * Seed a minimal active catalog when production starts empty.
     */
    public function run(): void
    {
        $catalog = [
            [
                'name' => 'Polos',
                'description' => 'Polos oficiales de la marca.',
                'sort_order' => 1,
                'products' => [
                    [
                        'name' => 'Polo ESO Classic',
                        'description' => 'Polo de algodon con logo frontal.',
                        'price' => 79.90,
                        'sale_price' => 69.90,
                        'stock' => 20,
                        'sku' => 'ESO-POLO-001',
                        'is_featured' => true,
                    ],
                ],
            ],
            [
                'name' => 'Accesorios',
                'description' => 'Complementos oficiales para la tienda.',
                'sort_order' => 2,
                'products' => [
                    [
                        'name' => 'Gorra ESO Signature',
                        'description' => 'Gorra premium ajustable.',
                        'price' => 59.90,
                        'sale_price' => null,
                        'stock' => 15,
                        'sku' => 'ESO-GORRA-001',
                        'is_featured' => true,
                    ],
                    [
                        'name' => 'Sticker Pack ESO',
                        'description' => 'Pack de stickers resistentes.',
                        'price' => 19.90,
                        'sale_price' => null,
                        'stock' => 50,
                        'sku' => 'ESO-STICKER-001',
                        'is_featured' => false,
                    ],
                ],
            ],
        ];

        foreach ($catalog as $categoryData) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                [
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                    'is_active' => true,
                    'sort_order' => $categoryData['sort_order'],
                ],
            );

            foreach ($categoryData['products'] as $productData) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($productData['name'])],
                    [
                        'name' => $productData['name'],
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'sale_price' => $productData['sale_price'],
                        'stock' => $productData['stock'],
                        'sku' => $productData['sku'],
                        'category_id' => $category->id,
                        'is_active' => true,
                        'is_featured' => $productData['is_featured'],
                    ],
                );
            }
        }
    }
}
