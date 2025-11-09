<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'description' => 'Insights, news, and tutorials on the latest technological advancements and innovations.',
            ],
            [
                'name' => 'Beauty & Personal Care',
                'description' => 'Tips, trends, and guides on skincare, makeup, haircare, and overall personal grooming.',
            ],
            [
                'name' => 'Food & Beverage',
                'description' => 'Recipes, reviews, and news on food, drinks, culinary trends, and dining experiences.',
            ],
            [
                'name' => 'Health & Wellness',
                'description' => 'Advice, research, and tips on physical health, mental wellness, fitness, and a balanced lifestyle.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
