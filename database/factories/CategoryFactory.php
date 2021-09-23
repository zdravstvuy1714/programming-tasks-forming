<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categoryName = $this->faker->word();
        $categorySlug = Str::slug($categoryName);

        return [
            'name' => $this->faker->word(),
            'slug' => $categorySlug,
        ];
    }
}
