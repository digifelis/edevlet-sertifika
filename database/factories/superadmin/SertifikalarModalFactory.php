<?php

namespace Database\Factories\superadmin;

use Illuminate\Database\Eloquent\Factories\Factory;

class SertifikalarModalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                
                    'ogrenciId' => $this->faker->numberBetween(1, 100),
                    'kursId' => $this->faker->numberBetween(1, 10),
                    'kurumId' => $this->faker->numberBetween(1, 10),
        ];
    }
}
