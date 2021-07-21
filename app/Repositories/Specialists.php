<?php

namespace App\Repositories;

use App\Helpers\JwtAuth;
use App\Models\Assign;
use App\Models\Commentary;
use App\Models\User;
use App\Notifications\TicketSent;

class Specialists implements SpecialistInterface
{
    private $token;

    public function __construct()
    {
        $this->token = request()->header('Authorization');
    }

    public function getTickets()
    {
        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        $allTickets = Assign::where([
            'assigned_id' => $decoded->sub,
        ])
            ->whereIn('status', [2, 3, 5, 6])
            ->orderBy('created_at', 'desc')
            ->with(['user', 'category', 'medio', 'state'])
            ->get(['id', 'user_id', 'category_id', 'media_id',
                'state_id', 'description', 'status',
            ]);

        return [
            'tickets' => $allTickets,
            'code' => 201
        ];
    }

    public function createComment($request)
    {
        $commentary = Commentary::create($request->all());
        $commentary->assigned()->sync($request->only('assgment_id'));
        $commentary->assigned()->update(['status' => 3]);

        return [
            'message' => 'Seguimiento creado.',
            'commentary' => $commentary,
            'code' => 201
        ];
    }

    public function getComments($id)
    {
        $specialist = Assign::with(['user', 'category', 'medio', 'state',
            'commentaries.specialist',
        ])
            ->select('id', 'user_id', 'category_id', 'media_id',
                'state_id', 'assigned_id', 'description', 'status'
            )
            ->whereIn('status', [2, 3, 5, 6])
            ->find($id);

        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        if (!$specialist):
            return [
                'message' => 'No existe el ticket',
                'code' => 404,
            ];
        endif;

        if ($specialist->assigned_id !== $decoded->sub):
            return [
                'message' => 'No tienes permiso para ejecutar esta accion',
                'code' => 401,
            ];
        endif;

        return [
            'ticket' => $specialist,
            'code' => 201
        ];
    }

    public function saveTicket($request, $asignacione)
    {
        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        if ($asignacione->assigned_id !== $decoded->sub):
            return [
                'message' => 'No tienes permiso para ejecutar esta accion',
                'code' => 401
            ];
        endif;

        $asignacione->update([
            'status_id' => $request->status_id,
            'modulo_id' => $request->modulo_id,
            'solution_id' => $request->solution_id,
            'status' => 4
        ]);

        $commentary = Commentary::create([
            'description' => $request->description,
            'assigned_id' => $asignacione->assigned_id,
            'status' => 2
        ]);

        $commentary->assigned()->sync($asignacione->id);

        return [
            'message' => 'El ticket se ha cerrado',
            'ticket' => $asignacione,
            'commentary' => $commentary,
            'code' => 201
        ];
    }

    public function reassigned($request, $reasign)
    {
        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        if ($reasign->assigned_id !== $decoded->sub):
            return [
                'message' => 'No tienes permiso para ejecutar esta accion',
                'code' => 401
            ];
        endif;

        $commentary = Commentary::create([
            'description' => $request->description,
            'assigned_id' => $decoded->sub,
            'status' => 3,
        ]);
        $commentary->assigned()->sync($reasign->id);
        $commentary->assigned()->update([
            'assigned_id' => $request->assigned_id,
            'status' => 5
        ]);

        $reasign['message'] = 'Tienes un ticket reasignado';
        $notification = User::find($request->assigned_id);
        $notification->notify(new TicketSent($reasign));

        return [
            'message' => 'Ticket reasignado',
            'commentary' => $commentary,
            'code' => 201
        ];
    }
}