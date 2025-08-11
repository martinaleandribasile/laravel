<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Request;
use App\Models\User;
use App\Models\ItemDetail;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $itemDetails = ItemDetail::all();
        $categories = Category::all();

        // Richieste inventario
        foreach ($users as $user) {
            for ($i = 0; $i < 8; $i++) {
                $itemDetail = $itemDetails->random();
                $start = now()->subDays(rand(1, 60));
                $end = (clone $start)->addDays(rand(1, 15));
                Request::create([
                    'user_id' => $user->id,
                    'item_detail_id' => $itemDetail->id,
                    'data_inizio' => $start->toDateString(),
                    'data_fine' => $end->toDateString(),
                    'note' => fake()->sentence(),
                    'stato' => Arr::random(['in_attesa', 'confermata', 'annullata']),
                    'tipo' => 'inventario',
                ]);
            }
        }

        // Richieste acquisto
        $nomi = ['Monitor 49" Samsung', 'Tastiera meccanica RGB', 'Mouse verticale', 'Cuffie gaming', 'Webcam 4K', 'Visore VR', 'Stampante laser', 'Dock USB-C', 'Sedia ergonomica', 'Tablet Android'];
        $catIds = [1,2,3,4,5,1,2,1,4,5];
        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $nome = $nomi[$i % count($nomi)];
                $catId = $catIds[$i % count($catIds)] ?? 1;
                $start = now()->subDays(rand(1, 60));
                $end = (clone $start)->addDays(rand(1, 15));
                Request::create([
                    'user_id' => $user->id,
                    'item_detail_id' => null,
                    'data_inizio' => $start->toDateString(),
                    'data_fine' => $end->toDateString(),
                    'note' => fake()->sentence(),
                    'stato' => Arr::random(['in_attesa', 'confermata', 'annullata']),
                    'tipo' => 'acquisto',
                    'nome_articolo' => $nome,
                    'category_id' => $catId,
                ]);
            }
        }
    }
}
