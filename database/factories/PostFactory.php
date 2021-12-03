<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isPrivate = $this->faker->boolean();
        return [
            'isPrivate' => $isPrivate,
            'title' => $this->faker->sentences($nb = 1, $asText = true),
            'message' => $this->faker->paragraph(),
            'sender' => $this->faker->numberBetween(1,10),
            'receiver' => $isPrivate ? $this->faker->numberBetween(1,10) : null,
        ];
    }
}
