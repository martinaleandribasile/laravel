<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemDetail;
use App\Models\ItemDetailUsage;
use App\Models\User;
use App\Models\Request;
use Illuminate\Support\Arr;

class ItemDetailUsageSeeder extends Seeder
{
    public function run(): void
    {
        $itemDetails = ItemDetail::all();
        $users = User::all();

        // Prima genero richieste fittizie per ogni item_detail
        $allRequests = collect();
        foreach ($itemDetails as $detail) {
            $numUsi = rand(2, 5);
            $start = now()->subDays(rand(30, 180));
            for ($i = 0; $i < $numUsi; $i++) {
                $user = $users->random();
                $data_inizio = (clone $start)->addDays(rand(1, 10));
                $start = (clone $data_inizio)->subDays(7)->addDays(rand(0, 2));
                $data_fine = (clone $data_inizio)->addDays(rand(2, 15));
                $request = Request::create([
                    'user_id' => $user->id,
                    'item_detail_id' => $detail->id,
                    'data_inizio' => $data_inizio->toDateString(),
                    'data_fine' => $data_fine->toDateString(),
                    'note' => fake()->sentence(),
                    'stato' => Arr::random(['in_attesa', 'confermata', 'annullata']),
                    'tipo' => 'inventario',
                    'created_at' => $start,
                    'updated_at' => $start,
                ]);
                $allRequests->push($request);
                ItemDetailUsage::create([
                    'item_detail_id' => $detail->id,
                    'user_id' => $user->id,
                    'request_id' => $request->id,
                    'data_inizio' => $data_inizio->toDateString(),
                    'data_fine' => $data_fine->toDateString(),
                ]);
                $start = (clone $data_fine)->addDays(rand(1, 10));
            }
        }
    }
}
