<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'organization', 'role', 'is_verified', 'avatar'
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',  // ← Tambahkan ini
];

    public function rentals()
    {
        return $this->hasMany(VMRental::class);
    }

    public function isAdmin()
    {
        return strtolower($this->role ?? '') === 'admin';
    }
}