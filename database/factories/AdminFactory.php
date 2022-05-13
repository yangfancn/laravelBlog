<?php

namespace Database\Factories;

use  JetBrains\PhpStorm\ArrayShape;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\JetBrains\PhpStorm\ArrayShape(['name' => "string", 'password' => "string", 'photo' => 'string'])]
    public function definition(): array
    {
        return [
            'name' => 'fx112',
            'password' => bcrypt('098a4d9f'),
            'photo' => asset('/admin/pictures/default-user.jpg')
        ];
    }
}
