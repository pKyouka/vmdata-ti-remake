<?php
// app/Models/VMRental.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VMRental extends Model
{
    use HasFactory;
    protected $table = 'vm_rentals';
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

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'access_credentials' => 'array',
        'reset_requested' => 'boolean',
        'reset_requested_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vm()
    {
        return $this->belongsTo(VM::class);
    }

    public function getDurationInHours()
    {
        return $this->start_time->diffInHours($this->end_time);
    }

    public function calculateCost()
    {
        $hours = $this->getDurationInHours();
        if (!$this->vm || !$this->vm->specification) {
            return 0; // Or calculate based on manual specs if logic exists
        }
        $pricePerHour = $this->vm->specification->price_per_hour;
        return $hours * $pricePerHour;
    }
}