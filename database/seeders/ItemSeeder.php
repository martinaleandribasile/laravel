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
            ['name' => 'Monitor LG 27"', 'description' => 'Monitor IPS FullHD', 'status' => 'available', 'quantity' => 5, 'category' => 'Monitor'],
            ['name' => 'Tastiera Logitech K120', 'description' => 'Tastiera cablata', 'status' => 'available', 'quantity' => 10, 'category' => 'Tastiere'],
            ['name' => 'Mouse Logitech M185', 'description' => 'Mouse wireless', 'status' => 'available', 'quantity' => 8, 'category' => 'Mouse'],
            ['name' => 'Cuffie Sony WH-CH510', 'description' => 'Cuffie wireless', 'status' => 'unavailable', 'quantity' => 2, 'category' => 'Cuffie'],
            ['name' => 'Webcam Logitech C920', 'description' => 'Webcam FullHD', 'status' => 'available', 'quantity' => 3, 'category' => 'Webcam'],
        ];
        foreach ($items as $item) {
            $cat = $categories->firstWhere('name', $item['category']);
            if ($cat) {
                Item::create([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'category_id' => $cat->id,
                    'status' => $item['status'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }
    }
}
