<?php
//app/Models/VM.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class VM extends Model
{
    use HasFactory;
    protected $table = 'vms';

    protected $fillable = [
        'name',
        'category_id',
        'specification_id',
        'ram',
        'cpu',
        'server_id',
        'ip_address',
        'storage',
        'status',
        'description',
        // access credentials
        'access_username',
        'access_password'
    ];

    protected $casts = [
        'ports' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function specification()
    {
        return $this->belongsTo(VMSpecification::class, 'specification_id');
    }

    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id');
    }

    /**
     * Get all rentals for this VM
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'vm_id');
    }

    /**
     * Get the current active rental for this VM
     */
    public function currentRental()
    {
        return $this->hasOne(Rental::class, 'vm_id')->where('status', 'active')->latest();
    }

    /**
     * Get the active rental attribute
     */
    public function getActiveRentalAttribute()
    {
        return $this->currentRental()->first();
    }

    /**
     * Check if VM is available for rental
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    /**
     * Store the access password encrypted in the database.
     */
    public function setAccessPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['access_password'] = null;
            return;
        }

        // encrypt before storing
        $this->attributes['access_password'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the access password when reading.
     * Returns null when not set or decryption fails.
     */
    public function getAccessPasswordAttribute($value)
    {
        if (empty($value))
            return null;

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}