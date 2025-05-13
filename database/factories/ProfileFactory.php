<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'admin_id' => Admin::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'image_path' => 'profiles/'.fake()->uuid().'.jpg',
            'status' => fake()->randomElement(['inactive', 'pending', 'active']),
        ];
    }
}
