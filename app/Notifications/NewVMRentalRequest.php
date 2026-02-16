<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

class NewVMRentalRequest extends Notification
{
    use Queueable;

    protected $rental;

    public function __construct($rental)
    {
        $this->rental = $rental;
    }

    public function via($notifiable)
    {
        return ['database','mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'rental_id' => $this->rental->id ?? null,
            'user_id' => $this->rental->user_id ?? null,
            'vm_id' => $this->rental->vm_id ?? null,
            'message' => 'Permintaan sewa VM baru: ' . ($this->rental->vm->name ?? 'VM'),
            'url' => route('vmrentals.show', $this->rental->id ?? 0),
        ];
    }

    public function toMail($notifiable)
    {
        $user = $this->rental->user->name ?? 'User';
        $vm = $this->rental->vm->name ?? 'VM';
        return (new MailMessage)
                    ->subject('Permintaan Sewa VM Baru')
                    ->line("User {$user} mengajukan permintaan sewa untuk VM: {$vm}.")
                    ->action('Lihat Permintaan', url(route('vmrentals.show', $this->rental->id ?? 0)))
                    ->line('Silakan cek panel admin untuk merespon.');
    }
}
