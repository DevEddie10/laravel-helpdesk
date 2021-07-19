<?php

namespace App\Repositories;

use App\Helpers\JwtAuth;
use App\Models\Assign;

class Assignments implements AssignmentsInterface
{
    private $token;

    public function __construct()
    {
        $this->token = request()->header('Authorization');
    }

    public function getTickets()
    {
        return Assign::where([
            'status' => 1,
        ])
            ->orderBy('id', 'desc')
            ->with(['user', 'category', 'medio', 'state'])->get();
    }

    public function createTicket($request)
    {
        return [
            'ticket' => Assign::create($request->all()),
            'message' => 'Se ha creado el ticket correctamente',
        ];
    }

    public function getTicket($id)
    {
        return Assign::where([
            'user_id' => $id,
        ])
            ->orderBy('id', 'desc')
            ->with(['user', 'category', 'medio', 'state', 'specialist', 'commentaries'])
            ->get(['id', 'user_id', 'category_id', 'media_id',
                'state_id', 'assigned_id', 'description', 'status',
            ]);
    }

    public function assingSpecialist($request, $ticket)
    {
        $ticket->update([
            'assigned_id' => $request->assigned_id,
            'status' => 2,
        ]);

        return [
            'message' => 'El ticket se ha asignado',
            'ticket' => $ticket,
        ];
    }

    public function monitoringTicket($id)
    {
        return Assign::where([
            'status' => $id,
        ])
            ->orderBy('id', 'desc')
            ->with(['user', 'category', 'medio', 'state', 'specialist', 'commentaries'])
            ->get(['id', 'user_id', 'category_id', 'media_id',
                'state_id', 'description', 'assigned_id', 'status',
            ]);
    }

    public function endupTicket($id)
    {
        return Assign::where([
            'id' => $id,
            'status' => 4,
        ])
            ->orderBy('id', 'desc')
            ->with(['user', 'category', 'medio', 'state', 'specialist',
                'module', 'solution', 'commentaries.specialist'])
            ->first(['id', 'user_id', 'category_id', 'media_id',
                'state_id', 'description', 'assigned_id',
                'modulo_id', 'solution_id', 'status',
            ]);
    }

    public function reactivate($request, $assign)
    {
        $assign->update(['status' => 6]);

        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        $assign->commentaries()->create([
            'description' => $request->description,
            'status' => $request->status,
            'assigned_id' => $decoded->sub,
        ]);

        return [
            'message' => 'El ticket se ha reactivado',
            'ticket' => $assign,
            'code' => 201,
        ];
    }

    public function finished($request, $assigned)
    {
        $assigned->update(['status' => 7]);

        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        $assigned->commentaries()->create([
            'description' => $request->description,
            'status' => $request->status,
            'assigned_id' => $decoded->sub,
        ]);

        return [
            'message' => 'El ticket se ha terminado',
            'ticket' => $assigned,
            'code' => 201,
        ];
    }
}