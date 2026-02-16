<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VMSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /* DB::table('v_m_specifications')->insert([
            [
                'name' => 'VM Basic',
                'ram' => 4,
                'storage' => 128,
                'backup_disk' => 10,
                'description' => 'Paket dasar untuk penggunaan ringan',
                'status' => 'available'
            ],
            [
                'name' => 'VM Standard',
                'ram' => 8,
                'storage' => 256,
                'backup_disk' => 20,
                'description' => 'Paket standar untuk kebutuhan menengah',
                'status' => 'available'
            ],
            
        ]);
        */
    }
}
