<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'admin_id' => Admin::factory(),
            'profile_id' => Profile::factory(),
            'content' => fake()->paragraph(),
        ];
    }
}
