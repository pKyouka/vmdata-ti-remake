<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'user_id',
        'vm_id',
        'admin_id',
        'start_date',
        'end_date',
        'status',
        'vm_username',
        'vm_password',
        'vm_ip_address',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Relasi ke User (penyewa)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke VM
     */
    public function vm()
    {
        return $this->belongsTo(VM::class);
    }

    /**
     * Relasi ke Admin (penanggung jawab rental)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Check if rental is currently active
     */
    public function isActive()
    {
        return $this->status === 'active' &&
            now()->between($this->start_date, $this->end_date);
    }

    /**
     * Check if rental has expired
     */
    public function isExpired()
    {
        return now()->greaterThan($this->end_date);
    }

    /**
     * Get rental duration in days
     */
    public function getDurationInDays()
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Scope for active rentals
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for pending rentals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
