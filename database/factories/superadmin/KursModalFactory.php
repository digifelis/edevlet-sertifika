<?php

namespace Database\Factories\superadmin;

use Illuminate\Database\Eloquent\Factories\Factory;

class KursModalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'kursAdi' => $this->faker->name(),
            'sertifikaAdi' => $this->faker->name(),
            'baslik' => $this->faker->name(),
            'aciklama' => $this->faker->name(),
            'dilKey' => 'tr',
            'tur' => 'Kurs Belgesi',
            'sertifikaGecerlilikTarihi' => $this->faker->date(),
            'baslangicTarihi' => $this->faker->date(),
            'bitisTarihi' => $this->faker->date(),
            'kursKurumId' => $this->faker->numberBetween(1, 10),
            'sablonDosyasi' => '1697140903_sablon1docx.docx',
            
        ];
    }
}
