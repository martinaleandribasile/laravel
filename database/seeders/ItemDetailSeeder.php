<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemDetail;
use Illuminate\Support\Str;

class ItemDetailSeeder extends Seeder
{
    public function run(): void
    {
        $items = Item::all();
        foreach ($items as $item) {
            for ($i = 1; $i <= 5; $i++) {
                $stato = fake()->randomElement(['disponibile', 'in_uso', 'in_attesa']);
                $data_inizio = $stato === 'in_uso' ? now()->subDays(rand(1, 30))->toDateString() : null;
                $data_fine = $stato === 'in_uso' && rand(0,1) ? now()->toDateString() : null;
                ItemDetail::create([
                    'item_id' => $item->id,
                    'seriale' => Str::random(8) . $i,
                    'colore' => fake()->randomElement(['nero', 'grigio', 'argento']),
                    'ram' => fake()->randomElement(['8GB', '16GB', '32GB']),
                    'altro' => fake()->randomElement(['SSD 256GB', 'SSD 512GB', 'HDD 1TB']),
                    'stato' => $stato,
                    'data_inizio_uso' => $data_inizio,
                    'data_fine_uso' => $data_fine,
                ]);
            }
        }
    }
}
