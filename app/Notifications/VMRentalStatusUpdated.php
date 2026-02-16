<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

class VMRentalStatusUpdated extends Notification
{
    use Queueable;

    protected $rental;
    protected $action;
    protected $admin;

    public function __construct($rental, $action = 'updated', $admin = null)
    {
        $this->rental = $rental;
        $this->action = $action;
        $this->admin = $admin;
    }

    public function via($notifiable)
    {
        return ['database','mail'];
    }

    public function toDatabase($notifiable)
    {
        $verb = $this->action === 'approve' ? 'disetujui' : 'ditolak';
        return [
            'rental_id' => $this->rental->id ?? null,
            'vm' => $this->rental->vm->name ?? null,
            'message' => "Permintaan sewa untuk VM " . ($this->rental->vm->name ?? 'VM') . " telah {$verb}.",
            'action' => $this->action,
            'url' => route('vmrentals.show', $this->rental->id ?? 0),
        ];
    }

    public function toMail($notifiable)
    {
        $verb = $this->action === 'approve' ? 'disetujui' : 'ditolak';
        $vm = $this->rental->vm->name ?? 'VM';
        return (new MailMessage)
                    ->subject('Status Permintaan Sewa VM')
                    ->line("Permintaan sewa Anda untuk VM: {$vm} telah {$verb} oleh admin.")
                    ->action('Lihat Permintaan', url(route('vmrentals.show', $this->rental->id ?? 0)))
                    ->line('Terima kasih.');
    }
}
