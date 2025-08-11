<?php
// Factory per generare dati fittizi di articoli (Item) per i test e i seed del database.
namespace Database\Factories;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' ' . $this->faker->randomNumber(3),
            'category_id' => Category::factory(),
            'description' => $this->faker->sentence(),
        ];
    }
}
