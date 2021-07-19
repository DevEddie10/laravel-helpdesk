<?php

namespace App\Observers;

use App\Models\Assign;
use App\Models\User;
use App\Notifications\TicketSent;

class AssignObserver
{
    /**
     * Handle the Assign "created" event.
     *
     * @param  \App\Models\Assign  $assign
     * @return void
     */
    public function created(Assign $assign)
    {
        //
    }

    /**
     * Handle the Assign "updated" event.
     *
     * @param  \App\Models\Assign  $assign
     * @return void
     */
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

    /**
     * Handle the Assign "deleted" event.
     *
     * @param  \App\Models\Assign  $assign
     * @return void
     */
    public function deleted(Assign $assign)
    {
        //
    }

    /**
     * Handle the Assign "restored" event.
     *
     * @param  \App\Models\Assign  $assign
     * @return void
     */
    public function restored(Assign $assign)
    {
        //
    }

    /**
     * Handle the Assign "force deleted" event.
     *
     * @param  \App\Models\Assign  $assign
     * @return void
     */
    public function forceDeleted(Assign $assign)
    {
        //
    }
}