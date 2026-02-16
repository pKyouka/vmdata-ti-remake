<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// app/Models/Category.php

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function vms()
    {
        return $this->hasMany(VM::class);
    }
}

