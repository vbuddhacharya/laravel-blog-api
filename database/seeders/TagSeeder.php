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
            ['name' => 'AI'],
            ['name' => 'Technology'],
            ['name' => 'Skincare'],
            ['name' => 'Beauty'],
            ['name' => 'Recipes'],
            ['name' => 'Food'],
            ['name' => 'Wellness'],
            ['name' => 'Health'],
            ['name' => 'Fitness'],
        ];


        foreach ($tags as $tag) {
            Tag::firstOrCreate($tag);
        }
    }
}
