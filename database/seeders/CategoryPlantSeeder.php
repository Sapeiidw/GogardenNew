<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Seeder;

class CategoryPlantSeeder extends Seeder
{
    public function run()
    {
        Plant::findOrFail(1)->categories()->sync([1,5,2,6]);
        Plant::findOrFail(2)->categories()->sync([2,3,4,7]);
        Plant::findOrFail(3)->categories()->sync([1,4,5,6]);
        Plant::findOrFail(4)->categories()->sync([2,3,5,7]);
        Plant::findOrFail(5)->categories()->sync([1,2,4,5]);
    }
}