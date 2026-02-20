<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(['name' => 'Residensial']);
        Category::firstOrCreate(['name' => 'Komersial']);
        Category::firstOrCreate(['name' => 'Kantor']);
        Category::firstOrCreate(['name' => 'Retail']);
    }
}
