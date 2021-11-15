<?php

namespace App\Http\Controllers\tickets;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTicketRequest;
use App\Repositories\Assignments;

class UserTicketController extends Controller
{
    protected $repository;

    public function __construct(Assignments $assignment)
    {
        $this->middleware('api.auth');
        $this->repository = $assignment;
    }

    public function store(CreateTicketRequest $request)
    {
        $response = $this->repository->createTicket($request);

        return response()->json($response, 201);
    }

    public function show(int $id)
    {
        $response = $this->repository->getTicket($id);

        return response()->json(['assigned' => $response], 201);
    }
}