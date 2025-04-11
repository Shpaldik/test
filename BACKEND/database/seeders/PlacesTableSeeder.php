<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Place;

class PlacesTableSeeder extends Seeder
{
    public function run()
    {
        // Создаём 10 случайных записей
        Place::factory()->count(10)->create();
    }
}
