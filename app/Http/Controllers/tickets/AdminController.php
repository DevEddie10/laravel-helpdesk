<?php

namespace App\Http\Controllers\tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssingSpecialistRequest;
use App\Http\Requests\ReactiveTicketRequest;
use App\Models\Assign;
use App\Repositories\Assignments;

class AdminController extends Controller
{
    protected $repository;

    public function __construct(Assignments $assignments)
    {
        $this->middleware('api.auth');
        $this->repository = $assignments;
    }

    public function index()
    {
        $tickets = $this->repository->getTickets();

        return response()->json(['tickets' => $tickets], 200);
    }

    public function update(AssingSpecialistRequest $request, Assign $ticket)
    {
        $assigned = $this->repository->assingSpecialist($request, $ticket);

        return response()->json($assigned, 201);
    }

    public function reactivate(ReactiveTicketRequest $request, Assign $assign)
    {
        $data = $this->repository->reactivateTicket($request, $assign);

        return response()->json($data, $data['code']);
    }

    public function finalize(ReactiveTicketRequest $request, Assign $assign)
    {
        $data = $this->repository->endTicket($request, $assign);

        return response()->json($data, $data['code']);
    }

    public function count()
    {
        return $this->repository->countTickets();
    }

    public function monitoring(int $id)
    {
        $data = $this->repository->monitoringTicket($id);

        return response()->json(['assignments' => $data]);
    }

    public function finished($id)
    {
        $data = $this->repository->endupTicket($id);

        return response()->json(['result' => $data]);
    }
}