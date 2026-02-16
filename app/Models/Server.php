<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'local_network',
        'ip_address',
        'status',
        'description'
    ];
    // Tambahkan relasi ini
    public function vms()
    {
        return $this->hasMany(\App\Models\VM::class, 'server_id');
    }
}
