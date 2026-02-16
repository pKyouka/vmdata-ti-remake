<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VMSpecification extends Model
{
    protected $table = 'v_m_specifications';

    protected $fillable = [
        'name',
        'ram',
        'storage',
        
        'description',
    ];

    public function vms()
    {
        return $this->hasMany(VM::class, 'specification_id');
    }
}

