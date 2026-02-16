<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Basic', 'slug' => 'basic', 'description' => 'Paket dasar untuk penggunaan ringan'],
            ['name' => 'Standard', 'slug' => 'standard', 'description' => 'Paket standar untuk kebutuhan menengah'],
            ['name' => 'Premium', 'slug' => 'premium', 'description' => 'Paket premium untuk performa tinggi'],
            ['name' => 'Elite', 'slug' => 'elite', 'description' => 'Paket terbaik untuk kebutuhan mission-critical'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
