<?php

namespace Database\Factories;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Laporan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'jenis_laporan' => $this->faker->randomElement(['masuk', 'keluar']),
            'kode_barang' => $this->faker->unique()->bothify('BRG-####'),
            'nama_barang' => $this->faker->words(3, true),
            'jumlah' => $this->faker->numberBetween(1, 100),
            'satuan' => 'pcs',
            'keterangan' => $this->faker->sentence,
            'lokasi' => $this->faker->word,
            'user_id' => User::factory(),
        ];
    }
}
