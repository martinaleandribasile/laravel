<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $items = [
            ['name' => 'Monitor LG 27"', 'description' => 'Monitor IPS FullHD', 'category' => 'Monitor'],
            ['name' => 'Tastiera Logitech K120', 'description' => 'Tastiera cablata', 'category' => 'Tastiere'],
            ['name' => 'Mouse Logitech M185', 'description' => 'Mouse wireless', 'category' => 'Mouse'],
            ['name' => 'Cuffie Sony WH-CH510', 'description' => 'Cuffie wireless', 'category' => 'Cuffie'],
            ['name' => 'Webcam Logitech C920', 'description' => 'Webcam FullHD', 'category' => 'Webcam'],
        ];
        foreach ($items as $item) {
            $cat = $categories->firstWhere('name', $item['category']);
            if ($cat) {
                Item::create([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'category_id' => $cat->id,
                ]);
            }
        }
    }
}
