<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all('id');

        for ($i = 0; $i < 100; $i++) {
            Task::factory()->create([
                'category_id' => $categories->random(),
            ]);
        }
    }
}
