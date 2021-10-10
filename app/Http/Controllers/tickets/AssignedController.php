<?php

namespace App\Http\Controllers\tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssingSpecialistRequest;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\ReactiveTicketRequest;
use App\Models\Assign;
use App\Repositories\Assignments;

class AssignedController extends Controller
{
    protected $assignment;

    public function __construct(Assignments $assignment)
    {
        $this->middleware('api.auth');
        $this->assignment = $assignment;
    }

    public function index()
    {
        $assigned = $this->assignment->getTickets();

        return response()->json(['tickets' => $assigned], 201);
    }

    public function store(CreateTicketRequest $request)
    {
        $assigned = $this->assignment->createTicket($request);

        return response()->json($assigned, 201);
    }

    public function show(int $id)
    {
        $assigned = $this->assignment->getTicket($id);

        return response()->json(['assigned' => $assigned], 201);
    }

    public function update(AssingSpecialistRequest $request, Assign $ticket)
    {
        $assigned = $this->assignment->assingSpecialist($request, $ticket);

        return response()->json($assigned, 201);
    }

    public function monitoringTicket(int $id)
    {
        $assigned = $this->assignment->monitoringTicket($id);

        return response()->json(['assignments' => $assigned]);
    }

    public function endupTicket($id)
    {
        $assigned = $this->assignment->endupTicket($id);

        return response()->json(['result' => $assigned]);
    }

    public function reactivateTicket(ReactiveTicketRequest $request, Assign $assign)
    {
        $data = $this->assignment->reactivate($request, $assign);

        return response()->json($data, $data['code']);
    }

    public function finishedTicket(ReactiveTicketRequest $request, Assign $assign)
    {
        $assigned = $this->assignment->finished($request, $assign);

        return response()->json($assigned, $assigned['code']);
    }

    public function countTickets()
    {
        return $this->assignment->count();      
    }
}