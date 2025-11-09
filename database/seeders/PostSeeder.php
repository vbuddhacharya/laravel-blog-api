<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'The Rise of AI in Everyday Technology',
                'content' => 'Exploring how artificial intelligence is transforming the tech landscape, from smart devices to cloud computing.',
                'user_id' => 3,
                'category_id' => 1,
            ],
            [
                'title' => 'Top 10 Skincare Tips for Healthy Skin',
                'content' => 'Discover the most effective skincare routines and products to maintain glowing, healthy skin.',
                'user_id' => 4,
                'category_id' => 2,
            ],
            [
                'title' => 'Delicious Summer Recipes to Try at Home',
                'content' => 'A collection of easy-to-make, refreshing dishes and beverages perfect for the summer season.',
                'user_id' => 3,
                'category_id' => 3,
            ],
            [
                'title' => '10 Ways to Improve Your Mental and Physical Health',
                'content' => 'Practical tips and strategies for maintaining both mental wellness and physical fitness in everyday life.',
                'user_id' => 4,
                'category_id' => 4,
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }

        Post::find(1)->tags()->attach([1, 2]);
        Post::find(2)->tags()->attach([3, 4]);
        Post::find(3)->tags()->attach([5, 6]);
        Post::find(4)->tags()->attach([7, 8, 9]);
    }
}
