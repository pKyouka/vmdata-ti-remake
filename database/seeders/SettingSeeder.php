<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $defaults = [
            ['key' => 'site_name', 'value' => config('app.name', 'Datacenter TI'), 'type' => 'string', 'description' => 'Nama aplikasi yang tampil di header'],
            ['key' => 'support_email', 'value' => env('MAIL_FROM_ADDRESS', 'support@example.com'), 'type' => 'string', 'description' => 'Email dukungan pelanggan'],
            ['key' => 'vm_default_category', 'value' => 'basic', 'type' => 'string', 'description' => 'Kategori default untuk VM baru'],
            ['key' => 'vm_allow_public', 'value' => '0', 'type' => 'boolean', 'description' => 'Apakah VM dapat diakses publik? (0/1)'],
        ];

        foreach ($defaults as $d) {
            Setting::updateOrCreate(['key' => $d['key']], $d);
        }
    }
}
