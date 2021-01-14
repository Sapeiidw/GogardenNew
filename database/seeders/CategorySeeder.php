<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'title' => 'Herbal',
            ],
            [
                'title' => 'House Plant',
            ],
            [
                'title' => 'Fruit',
            ],
            [
                'title' => 'Sayur',
            ],
            [
                'title' => 'Tree',
            ],
            [
                'title' => 'Umbi',
            ],
            [
                'title' => 'Liar',
            ],
            
        ];

        Category::insert($categories);
    }
}
