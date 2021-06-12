<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Assign;
use App\Helpers\JwtAuth;
use App\Models\Commentary;
use App\Notifications\TicketSent;
use Illuminate\Support\Facades\Validator;

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

        if ($allTickets):
            $data = [
                'status' => 'success',
                'code' => 200,
                'tickets' => $allTickets,
            ];
        else:
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'No tienes tickets asigandos',
            ];
        endif;

        return $data;
    }

    public function createComment($request)
    {
        $validated = Validator::make($request->all(), [
            'description' => 'required',
            'status' => 'required',
            'assgment_id' => 'required',
            'assigned_id' => 'required',
        ]);

        if ($validated->fails()):
            $data = [
                'status' => 'info',
                'code' => 404,
                'errors' => $validated->errors(),
            ];
        else:
            $commentary = Commentary::create($request->all());
            $commentary->assigned()->sync($request->only('assgment_id'));

            $commentary->assigned()->update([
                'status' => 3,
            ]);

            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Seguimiento creado.',
                'commentary' => $commentary
            ];
        endif;

        return $data;
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
            return $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'No existe el ticket',
            ];
        endif;

        if ($specialist->assigned_id !== $decoded->sub):
            return $data = [
                'status' => 'info',
                'code' => 404,
                'message' => 'No tienes permiso para ejecutar esta accion',
            ];
        endif;

        $data = [
            'status' => 'success',
            'code' => 200,
            'ticket' => $specialist,
        ];

        return $data;
    }

    public function saveTicket($request, $id)
    {
        $assing = Assign::find($id);

        if (!$assing):
            return $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'No existe el ticket',
            ];
        endif;

        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        if ($assing->assigned_id !== $decoded->sub):
            return $data = [
                'status' => 'info',
                'code' => 404,
                'message' => 'No tienes permiso para ejecutar esta accion',
            ];
        endif;

        $validated = Validator::make($request->all(), [
            'status_id' => 'required',
            'modulo_id' => 'required',
            'solution_id' => 'required',
            'description' => 'required',
        ]);

        if ($validated->fails()):
            $data = [
                'status' => 'error',
                'code' => 404,
                'errors' => $validated->errors(),
            ];
        else:
            $assing->update([
                'status_id' => $request->status_id,
                'modulo_id' => $request->modulo_id,
                'solution_id' => $request->solution_id,
                'status' => 4,
            ]);

            $commentary = Commentary::create([
                'description' => $request->description,
                'assigned_id' => $assing->assigned_id,
                'status' => 2,
            ]);

            $commentary->assigned()->sync($id);

            $data = [
                'status' => 'success',
                'code' => 200,
                'ticket' => $assing,
                'message' => 'El ticket se ha cerrado',
                'commentary' => $commentary,
            ];
        endif;

        return $data;
    }

    public function reassigned($request, $id)
    {
        $reasign = Assign::find($id);

        if (!$reasign):
            return $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'No existe el ticket',
            ];
        endif;

        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        if ($reasign->assigned_id !== $decoded->sub):
            return $data = [
                'status' => 'info',
                'code' => 404,
                'message' => 'No tienes permiso para ejecutar esta accion',
            ];
        endif;

        $validate = Validator::make($request->all(), [
            'description' => 'required',
            'assigned_id' => 'required',
        ]);

        if ($validate->fails()):
            $data = [
                'status' => 'info',
                'code' => 404,
                'errors' => $validate->errors(),
            ];
        else:
            $commentary = Commentary::create([
                'description' => $request->description,
                'assigned_id' => $decoded->sub,
                'status' => 3,
            ]);

            $commentary->assigned()->sync($id);

            $commentary->assigned()->update([
                'assigned_id' => $request->assigned_id,
                'status' => 5,
            ]);

            $reasign['message'] = 'Tienes un ticket reasignado';

            $notification = User::find($request->assigned_id);
            $notification->notify(new TicketSent($reasign));

            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Ticket reasignado',
                'commentary' => $commentary,
            ];
        endif;

        return $data;
    }
}
