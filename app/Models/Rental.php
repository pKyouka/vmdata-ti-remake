<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'user_id',
        'vm_id',
        'admin_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status',
        'vm_username',
        'vm_password',
        'vm_ip_address',
        'total_cost',
        'rental_type',
        'cpu',
        'ram',
        'storage',
        'purpose',
        'operating_system',
        'access_credentials',
        'reset_requested',
        'reset_requested_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'access_credentials' => 'array',
        'reset_requested' => 'boolean',
        'reset_requested_at' => 'datetime',
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
     * Check if rental is currently active based on dates (status-independent)
     * Only check date range without considering manually-set status
     */
    public function isActive()
    {
        $today = now()->startOfDay();
        return $today->between($this->start_date, $this->end_date);
    }

    /**
     * Check if rental has expired based on end_date (status-independent)
     */
    public function isExpired()
    {
        return now()->startOfDay()->greaterThan($this->end_date);
    }

    /**
     * Check if rental has started but is not yet active
     */
    public function isPending()
    {
        return now()->startOfDay()->lessThan($this->start_date);
    }

    /**
     * Get rental duration in days
     */
    public function getDurationInDays()
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Scope for active rentals - based on actual date range, not status field
     */
    public function scopeActive($query)
    {
        $today = now()->startOfDay();
        return $query->whereDate('start_date', '<=', $today)
                     ->whereDate('end_date', '>=', $today);
    }

    /**
     * Scope for expired rentals - based on end_date
     */
    public function scopeExpired($query)
    {
        return $query->whereDate('end_date', '<', now()->startOfDay());
    }

    /**
     * Scope for pending rentals - haven't started yet
     */
    public function scopePending($query)
    {
        return $query->whereDate('start_date', '>', now()->startOfDay());
    }
}
