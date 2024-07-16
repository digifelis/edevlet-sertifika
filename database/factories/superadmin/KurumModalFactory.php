<?php

namespace Database\Factories\superadmin;

use Illuminate\Database\Eloquent\Factories\Factory;

class KurumModalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'kurumAdi' => $this->faker->name(),
            'kurumKodu' => $this->faker->name(),
            'kullaniciAdi' => $this->faker->name(),
            'sifre' => '12345678',
            'lisansSayisi' => 100,
        ];
    }
}
