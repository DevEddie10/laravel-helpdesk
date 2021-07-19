<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketSent extends Notification
{
    protected $ticketId;

    use Queueable;

    public function __construct($ticketId)
    {
        $this->ticketId = $ticketId;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->ticketId->id,
            'text' => 'Ticket # '.$this->ticketId->id .' - '. $this->ticketId->message,
            'url' => '/seguimientos'.'/'.$this->ticketId->id
        ];
    }
}