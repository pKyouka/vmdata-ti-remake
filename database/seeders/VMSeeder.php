<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VM;
use App\Models\Server;
use App\Models\Category;
use App\Models\VMSpecification;

class VMSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada server, category, dan specification
        $server = Server::first();
        if (!$server) {
            $server = Server::create([
                'name' => 'Server Indonesia 1',
                'local_network' => '192.168.1.0/24',
                'ip_address' => '192.168.1.1',
                'status' => 'active',
                'description' => 'Server utama untuk deployment VM'
            ]);
        }

        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Development',
                'description' => 'VM untuk development dan testing'
            ]);
        }

        $spec = VMSpecification::first();
        if (!$spec) {
            $spec = VMSpecification::create([
                'name' => 'Standard',
                'ram' => 4,
                'storage' => 50,
                'category_id' => $category->id,
                'description' => 'Spesifikasi standard untuk aplikasi web'
            ]);
        }

        // Get a user to assign to VMs (admin user)
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        if (!$adminUser) {
            $this->command->error('No admin user found! Please create an admin user first.');
            return;
        }


        // Buat VM demo
        $vms = [
            [
                'name' => 'VM-Web-01',
                'category_id' => $category->id,
                'specification_id' => $spec->id,
                'ram' => 4,
                'cpu' => 2,
                'server_id' => $server->id,
                'ip_address' => '192.168.1.10',
                'storage' => 50,
                'status' => 'available',
                'description' => 'VM untuk web server',
                'user_id' => $adminUser->id
            ],
            [
                'name' => 'VM-DB-01',
                'category_id' => $category->id,
                'specification_id' => $spec->id,
                'ram' => 8,
                'cpu' => 4,
                'server_id' => $server->id,
                'ip_address' => '192.168.1.11',
                'storage' => 100,
                'status' => 'available',
                'description' => 'VM untuk database server',
                'user_id' => $adminUser->id
            ],
            [
                'name' => 'VM-APP-01',
                'category_id' => $category->id,
                'specification_id' => $spec->id,
                'ram' => 8,
                'cpu' => 4,
                'server_id' => $server->id,
                'ip_address' => '192.168.1.12',
                'storage' => 80,
                'status' => 'available',
                'description' => 'VM untuk application server',
                'user_id' => $adminUser->id
            ],
            [
                'name' => 'VM-Cache-01',
                'category_id' => $category->id,
                'specification_id' => $spec->id,
                'ram' => 2,
                'cpu' => 1,
                'server_id' => $server->id,
                'ip_address' => '192.168.1.13',
                'storage' => 20,
                'status' => 'available',
                'description' => 'VM untuk caching (Redis/Memcached)',
                'user_id' => $adminUser->id
            ],
            [
                'name' => 'VM-Dev-01',
                'category_id' => $category->id,
                'specification_id' => $spec->id,
                'ram' => 4,
                'cpu' => 2,
                'server_id' => $server->id,
                'ip_address' => '192.168.1.14',
                'storage' => 60,
                'status' => 'available',
                'description' => 'VM untuk development environment',
                'user_id' => $adminUser->id
            ],
        ];

        foreach ($vms as $vmData) {
            VM::create($vmData);
        }

        $this->command->info('5 VM demo berhasil dibuat!');
    }
}
