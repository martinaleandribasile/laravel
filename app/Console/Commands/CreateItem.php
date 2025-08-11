<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Category;

class CreateItem extends Command
// Esempio di comando da terminale:
// php artisan items:create --name="Monitor LED" --category="Monitor" --description="Monitor 27 pollici"
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
