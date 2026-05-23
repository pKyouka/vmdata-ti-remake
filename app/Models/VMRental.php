<?php
// app/Models/VMRental.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VMRental extends Rental
{
    use HasFactory;
    
    protected $table = 'rentals';
    
    protected static function boot()
    {
        parent::boot();
        
        // Auto-set rental_type when creating VMRental
        static::creating(function ($model) {
            $model->rental_type = 'vm_rental';
        });
    }
    
    protected static function newBaseQuery()
    {
        return parent::newBaseQuery()->where('rental_type', 'vm_rental');
    }
    
    protected $fillable = [
        'user_id',
        'vm_id',
        'start_time',
        'end_time',
        'cpu',
        'ram',
        'storage',
        'total_cost',
        'status',
        'purpose',
        'operating_system',
        'access_credentials',
        'reset_requested',
        'reset_requested_at'
    ];

    public function getDurationInHours()
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }
        return $this->start_time->diffInHours($this->end_time);
    }

    public function calculateCost()
    {
        $hours = $this->getDurationInHours();
        if (!$this->vm || !$this->vm->specification) {
            return 0;
        }
        $pricePerHour = $this->vm->specification->price_per_hour;
        return $hours * $pricePerHour;
    }
}