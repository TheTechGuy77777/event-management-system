<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Music',
            'Tech',
            'Fashion',
            'Comedy',
            'Film',
            'Health',
            'Food & Drink',
            'Sports & Wellness',
            'Art & Culture',
            'Career & Business',
            'Spirituality & Religion',
            'Community',
            'Education',
            'Gaming',
            'Travel',
            'Shopping',
            'Easter',
            'Christmas',
            'Funfairs & Carnivals',
            'Hiking',
            'Networking',
            'Visual Arts',
            'Books & Literature',
            'Gender & Equality',
            'Democracy & Policy',
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category],
                ['is_active' => true]
            );
        }
    }
}
