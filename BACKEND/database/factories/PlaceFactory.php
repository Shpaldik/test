<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Place;

class PlaceFactory extends Factory
{
    // Связываем фабрику с моделью Place
    protected $model = Place::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,   // Или city, или любое другое
            'type' => $this->faker->randomElement(['Restaurant', 'Attraction', 'Museum']),
            'latitude' => $this->faker->latitude,   // Генерирует рандомную широту
            'longitude' => $this->faker->longitude, // Генерирует рандомную долготу
            'x' => $this->faker->randomFloat(7, -180, 180),
            'y' => $this->faker->randomFloat(7, -90, 90),
            'image_path' => null,
            'description' => $this->faker->sentence,
            'open_time' => '08:30:00',
            'close_time' => '18:00:00',
            'num_searches' => $this->faker->numberBetween(0, 100),
        ];
    }
}
