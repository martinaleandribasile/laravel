<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemDetail;
use App\Models\ItemDetailUsage;
use Carbon\Carbon;

class UpdateItemDetailsStatus extends Command
// Esempio di comando da terminale:
// php artisan items:update-status
{
    protected $signature = 'items:update-status';
    protected $description = 'Aggiorna lo stato dei pezzi in base alle date di utilizzo e richieste';

    public function handle()
    {
        $oggi = Carbon::today();
        $itemDetails = ItemDetail::all();
        $count = 0;
        foreach ($itemDetails as $detail) {
            // Trova uso attivo
            $uso = ItemDetailUsage::where('item_detail_id', $detail->id)
                ->where('data_inizio', '<=', $oggi)
                ->where(function($q) use ($oggi) {
                    $q->whereNull('data_fine')->orWhere('data_fine', '>=', $oggi);
                })->first();
            if ($uso) {
                if ($detail->stato !== 'in_uso') {
                    $detail->update(['stato' => 'in_uso']);
                    $count++;
                }
                continue;
            }
            // Se non in uso, controlla richieste in attesa
            $hasPending = $detail->requests()->where('stato', 'in_attesa')->exists();
            if ($hasPending) {
                if ($detail->stato !== 'in_attesa') {
                    $detail->update(['stato' => 'in_attesa']);
                    $count++;
                }
                continue;
            }
            // Altrimenti disponibile
            if ($detail->stato !== 'disponibile') {
                $detail->update(['stato' => 'disponibile']);
                $count++;
            }
        }
        $this->info("Aggiornati $count pezzi.");
        return 0;
    }
}
