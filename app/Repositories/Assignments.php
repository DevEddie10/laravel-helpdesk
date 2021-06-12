<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Assign;
use App\Helpers\JwtAuth;
use App\Notifications\TicketSent;
use Illuminate\Support\Facades\Validator;

class Assignments implements AssignmentsInterface
{
    private $token;

    public function __construct()
    {
        $this->token = request()->header('Authorization');
    }

    public function getTickets()
    {
        $allTickets = Assign::where([
            'status' => 1,
        ])
            ->orderBy('id', 'desc')
            ->with(['user', 'category', 'medio', 'state'])->get();

        if ($allTickets):
            $data = [
                'status' => 'success',
                'code' => 200,
                'tickets' => $allTickets,
            ];
        endif;

        return $data;
    }

    public function createTicket($request)
    {
        $validated = Validator::make($request->all(), [
            'user_id' => 'required',
            'category_id' => 'required',
            'media_id' => 'required',
            'state_id' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validated->fails()):
            $data = [
                'status' => 'warning',
                'code' => 404,
                'errors' => $validated->errors(),
            ];
        else:
            $ticketAssign = Assign::create($request->all());

            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => 'Se ha creado el ticket correctamente',
                'ticket' => $ticketAssign,
            ];
        endif;

        return $data;
    }

    public function getTicket($id)
    {
        $assignedId = Assign::where([
            'user_id' => $id,
        ])
            ->orderBy('id', 'desc')
            ->with(['user', 'category', 'medio', 'state', 'specialist', 'commentaries'])
            ->get(['id', 'user_id', 'category_id', 'media_id',
                'state_id', 'assigned_id', 'description', 'status',
            ]);

        $data = [
            'status' => 'success',
            'code' => 200,
            'assigned' => $assignedId,
        ];

        return $data;
    }

    public function assingSpecialist($request, $id)
    {
        $ticketId = Assign::find($id);

        if ($ticketId):
            $validated = Validator::make($request->all(), [
                'assigned_id' => 'required',
            ]);

            if ($validated->fails()):
                $data = [
                    'status' => 'warning',
                    'code' => 404,
                    'errors' => $validated->errors(),
                ];
            else:
                $ticketId->update([
                    'assigned_id' => $request->assigned_id,
                    'status' => 2,
                ]);
                $ticketId['message'] = "Tienes asignado un ticket";

                $notification = User::find($request->assigned_id);
                $notification->notify(new TicketSent($ticketId));

                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El ticket se ha asignado',
                    'ticket' => $ticketId
                ];
            endif;
        else:
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'No existe el id del ticket',
            ];
        endif;

        return $data;
    }

    public function monitoringTicket($id)
    {
        $assignments = Assign::where([
            'status' => $id,
        ])
            ->orderBy('id', 'desc')
            ->with(['user', 'category', 'medio', 'state', 'specialist', 'commentaries'])
            ->get(['id', 'user_id', 'category_id', 'media_id',
                'state_id', 'description', 'assigned_id', 'status',
            ]);

        if ($assignments):
            $data = [
                'status' => 'success',
                'code' => 200,
                'assignments' => $assignments,
            ];
        endif;

        return $data;
    }

    public function endupTicket($id)
    {
        $result = Assign::where([
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

        if ($result):
            $data = [
                'status' => 'success',
                'code' => 200,
                'result' => $result,
            ];
        endif;

        return $data;
    }

    public function reactivate($request, $id)
    {
        $reactivate = Assign::find($id);

        if (!$reactivate):
            return $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'No existe el ticket',
            ];
        endif;

        $validated = Validator::make($request->all(), [
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validated->fails()):
            $data = [
                'status' => 'warning',
                'code' => 404,
                'errors' => $validated->errors(),
            ];
        else:
            $reactivate->status = 6;
            $reactivate->save();

            $jwt = new JwtAuth();
            $decoded = $jwt->checkToken($this->token, true);

            $reactivate->commentaries()->create([
                'description' => $request->description,
                'status' => $request->status,
                'assigned_id' => $decoded->sub,
            ]);

            $reactivate['message'] = 'Tienes un ticket reactivado';

            $notification = User::find($reactivate->assigned_id);
            $notification->notify(new TicketSent($reactivate));

            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => 'El ticket se ha reactivado',
                'ticket' => $reactivate,
            ];
        endif;

        return $data;
    }

    public function finished($request, $id)
    {
        $finished = Assign::find($id);

        if (!$finished):
            return $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'No existe el ticket',
            ];
        endif;

        $validated = Validator::make($request->all(), [
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validated->fails()):
            $data = [
                'status' => 'warning',
                'code' => 404,
                'errors' => $validated->errors(),
            ];
        else:
            $finished->status = 7;
            $finished->save();

            $jwt = new JwtAuth();
            $decoded = $jwt->checkToken($this->token, true);

            $finished->commentaries()->create([
                'description' => $request->description,
                'status' => $request->status,
                'assigned_id' => $decoded->sub,
            ]);

            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => 'El ticket se ha terminado',
                'ticket' => $finished,
            ];
        endif;

        return $data;
    }
}
