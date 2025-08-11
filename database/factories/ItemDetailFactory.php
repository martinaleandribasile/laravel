<?php

namespace Database\Factories;

use App\Models\ItemDetail;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemDetailFactory extends Factory
{
    protected $model = ItemDetail::class;

    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'seriale' => $this->faker->unique()->bothify('SN-####-??'),
            'colore' => $this->faker->safeColorName(),
            'ram' => $this->faker->randomElement(['4GB','8GB','16GB','32GB']),
            'altro' => $this->faker->word(),
            'stato' => 'disponibile',
            'data_inizio_uso' => null,
            'data_fine_uso' => null,
        ];
    }
}
