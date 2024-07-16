<?php

namespace Database\Factories\superadmin;

use Illuminate\Database\Eloquent\Factories\Factory;

class OgrencilerModalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ogrenciAdi' => $this->faker->name(),
            'ogrenciSoyadi' => $this->faker->name(),
            'tcKimlikNo' => $this->faker->numberBetween(10000000000, 99999999999),
            'kurumId' => $this->faker->numberBetween(1, 10),
        ];
    }
}
