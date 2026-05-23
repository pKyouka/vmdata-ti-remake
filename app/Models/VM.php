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
     * Get all VM rentals for this VM
     */
    public function vmRentals()
    {
        return $this->hasMany(VMRental::class, 'vm_id');
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
     * Encrypts with current app key.
     */
    public function setAccessPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['access_password'] = null;
            return;
        }

        $this->attributes['access_password'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the access password when reading.
     * Returns null if decryption fails (e.g., key rotated).
     * 
     * NOTE: After APP_KEY rotation, encrypted passwords can no longer be decrypted.
     * You should provide a migration to re-encrypt all passwords with the new key,
     * or store passwords in a separate encrypted vault.
     */
    public function getAccessPasswordAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Illuminate\Support\Facades\Log::error('VM password decryption failed', [
                'vm_id' => $this->id ?? 'unknown',
                'reason' => 'Encryption key may have been rotated',
            ]);
            return null;
        }
    }
}