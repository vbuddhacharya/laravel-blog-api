<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Tech',
            ],
            [
                'name' => 'Beauty',
            ],
            [
                'name' => 'DIY',
            ],
            [
                'name' => 'Travel',
            ],
            [
                'name' => 'Food',
            ],
            [
                'name' => 'Fashion',
            ],
            [
                'name' => 'Fitness',
            ],
            [
                'name' => 'Health',
            ],
            [
                'name' => 'Lifestyle',
            ],
            [
                'name' => 'Parenting',
            ],
            [
                'name' => 'Pets',
            ],
            [
                'name' => 'Sports',
            ],
            [
                'name' => 'Other',
            ],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate($tag);
        }
    }
}
