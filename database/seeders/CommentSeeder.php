<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments1 = [
            [
                'body' => 'Really insightful! AI is definitely changing the tech world.',
                'user_id' => 3,
            ],
            [
                'body' => 'Can you provide examples of AI in consumer products?',
                'user_id' => 4,
            ],

        ];

        $comments2 = [
            // Comments for Post 2: Beauty & Personal Care
            [
                'body' => 'These skincare tips are easy to follow and very effective!',
                'user_id' => 3,
            ],
            [
                'body' => 'I love the product recommendations you included.',
                'user_id' => 4,
            ],
        ];

        Post::find(1)->comments()->createMany($comments1);
        Post::find(2)->comments()->createMany($comments2);
    }
}
