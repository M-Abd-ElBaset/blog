<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'user_id'=>User::all()->random()->id,
            'title'=>$this->faker->sentence(4),
            'body'=>$this->faker->paragraph(2),
            'image'=>'images/fake.png',
            'pinned'=>$this->faker->boolean()
        ];
    }
}
