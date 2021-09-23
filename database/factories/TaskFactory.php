<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title'         => $this->faker->sentence(3),
            'content'       => $this->faker->text(500),
            'category_id'   => Category::factory(),
        ];
    }
}
