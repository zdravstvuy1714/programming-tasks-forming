<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Fundamentals',
            'String',
            'Algorithms',
            'Mathematic',
            'Performance',
            'Booleans',
            'Functions',
        ];

        foreach ($categories as $category) {
            $categoryName = $category;
            $categorySlug = Str::slug($category);

            Category::factory()->create([
                'name' => $categoryName,
                'slug' => $categorySlug,
            ]);
        }
    }
}
