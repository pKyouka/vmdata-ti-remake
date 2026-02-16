<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VMRental;

class VMRentalPolicy
{
    /** Determine whether the user can update the rental. */
    public function update(User $user, VMRental $rental)
    {
        return $user->role === 'admin' || $user->id === $rental->user_id;
    }

    /** Determine whether the user can delete the rental. */
    public function delete(User $user, VMRental $rental)
    {
        // allow admin or owner to delete only when status is pending or cancelled
        if ($user->role === 'admin') return true;
        return $user->id === $rental->user_id && in_array($rental->status, ['pending', 'cancelled']);
    }
}
