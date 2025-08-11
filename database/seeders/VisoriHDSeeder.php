<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemDetail;

class VisoriHDSeeder extends Seeder
{
    public function run(): void
    {
        // Crea l'item generico Visore HD
        $item = Item::create([
            'name' => 'Visore HD',
            'description' => 'Visore realtà aumentata ad alta definizione',
            'category_id' => 1, // Assicurati che la categoria 1 sia corretta o sostituisci con l'id giusto
            'quantity' => 8,
        ]);

        $caratteristiche = [
            ['colore' => 'nero', 'ram' => '8GB', 'altro' => 'SSD 256GB'],
            ['colore' => 'bianco', 'ram' => '16GB', 'altro' => 'SSD 512GB'],
            ['colore' => 'rosso', 'ram' => '8GB', 'altro' => 'HDD 1TB'],
            ['colore' => 'blu', 'ram' => '16GB', 'altro' => 'SSD 256GB'],
            ['colore' => 'grigio', 'ram' => '32GB', 'altro' => 'SSD 1TB'],
            ['colore' => 'nero', 'ram' => '8GB', 'altro' => 'SSD 512GB'],
            ['colore' => 'bianco', 'ram' => '16GB', 'altro' => 'HDD 2TB'],
            ['colore' => 'rosso', 'ram' => '32GB', 'altro' => 'SSD 2TB'],
        ];

        foreach ($caratteristiche as $i => $car) {
            ItemDetail::create([
                'item_id' => $item->id,
                'seriale' => 'HDVSR-' . str_pad($i+1, 3, '0', STR_PAD_LEFT),
                'colore' => $car['colore'],
                'ram' => $car['ram'],
                'altro' => $car['altro'],
                'stato' => 'disponibile',
            ]);
        }
    }
}
