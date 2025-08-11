<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Monitor', 'description' => 'Monitor di varie dimensioni'],
            ['name' => 'Tastiere', 'description' => 'Tastiere meccaniche e a membrana'],
            ['name' => 'Mouse', 'description' => 'Mouse ottici e wireless'],
            ['name' => 'Cuffie', 'description' => 'Cuffie e auricolari'],
            ['name' => 'Webcam', 'description' => 'Webcam HD e FullHD'],
            ['name' => 'Visore', 'description' => 'Visori VR e AR'],
        ];
        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
