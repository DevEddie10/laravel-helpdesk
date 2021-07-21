<?php

namespace App\Observers;

use App\Models\Assign;
use App\Models\User;
use App\Notifications\TicketSent;

class AssignObserver
{
    public function updated(Assign $ticket): void
    {
        $notification = User::find($ticket->assigned_id);

        if ($ticket->status == 2) {
            $ticket['message'] = "Tienes asignado un ticket";
            $notification->notify(new TicketSent($ticket));
        } elseif ($ticket->status == 6) {
            $ticket['message'] = 'Tienes un ticket reactivado';
            $notification->notify(new TicketSent($ticket));
        }
    }
}
