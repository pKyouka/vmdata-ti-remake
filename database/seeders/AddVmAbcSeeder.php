<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Server;
use App\Models\Category;
use App\Models\VMSpecification;
use App\Models\User;
use App\Models\VM;

class AddVmAbcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a server named 'Database Server' exists (case-insensitive)
        $server = Server::whereRaw('LOWER(name) = ?', [strtolower('Database Server')])->first();
        if (!$server) {
            $server = Server::create([
                'name' => 'Database Server',
                'local_network' => '192.168.100.0/24',
                'ip_address' => null,
                'description' => 'Auto-created server for seeder',
            ]);
        }

        // Find or create a category (Basic)
        $category = Category::firstOrCreate(['name' => 'Basic']);

        // Find or create a specification
        $spec = VMSpecification::first();
        if (!$spec) {
            $spec = VMSpecification::create([
                'name' => 'Default Spec',
                'ram' => 2,
                'storage' => 20,
                'backup_disk' => null,
                'description' => 'Default spec created by seeder',
            ]);
        }

        // Find or create a user to own the VM
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Seeder User',
                'email' => 'seeder@example.test',
                'password' => bcrypt('secret'),
                'role' => 'admin',
            ]);
        }

        // If VM 'abc' already exists, update server assignment; otherwise create it
        $vm = VM::where('name', 'abc')->first();
        if ($vm) {
            $vm->server_id = $server->id;
            $vm->category_id = $category->id;
            $vm->specification_id = $spec->id;
            $vm->user_id = $user->id;
            $vm->save();
            $this->command->info("Updated existing VM 'abc' to server 'Database Server' (id={$server->id}).");
            return;
        }

        VM::create([
            'name' => 'abc',
            'category_id' => $category->id,
            'specification_id' => $spec->id,
            'ram' => 2,
            'cpu' => 1,
            'server_id' => $server->id,
            'storage' => 20,
            'status' => 'available',
            'description' => 'VM created by AddVmAbcSeeder',
            'user_id' => $user->id,
        ]);

        $this->command->info("Created VM 'abc' on server 'Database Server' (id={$server->id}).");
    }
}
