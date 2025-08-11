<?php
namespace App\Console\Commands;

// Questo comando artisan permette di creare un nuovo articolo (item) in magazzino da terminale, specificando nome, categoria e descrizione.
// Esempio:
// php artisan items:create --name="Monitor LED" --category="Monitor" --description="Monitor 27 pollici"

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Category;

class CreateItem extends Command
{
    protected $signature = 'items:create {--name=} {--category=} {--description=}';
    protected $description = 'Crea un nuovo item (articolo) in magazzino';

    public function handle()
    {
        $name = $this->option('name');
        $categoryName = $this->option('category');
        $description = $this->option('description') ?? '';

        if (!$name || !$categoryName) {
            $this->error('Devi specificare --name e --category');
            return 1;
        }

        $category = Category::firstOrCreate(['name' => $categoryName]);
        $item = Item::create([
            'name' => $name,
            'category_id' => $category->id,
            'description' => $description,
        ]);

        $this->info("Item creato: {$item->name} (ID: {$item->id})");
        return 0;
    }
}
