<?php

namespace App\Http\Controllers\tickets;

use App\Http\Controllers\Controller;
use App\Repositories\Assignments;
use Illuminate\Http\Request;

class AssignedController extends Controller
{
    private $token;
    protected $assignment;

    public function __construct(Assignments $assignment)
    {
        $this->middleware('api.auth');
        $this->assignment = $assignment;
    }

    public function index()
    {
        $data = $this->assignment->getTickets();

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        $data = $this->assignment->createTicket($request);

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $data = $this->assignment->getTicket($id);

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id)
    {
        $data = $this->assignment->assingSpecialist($request, $id);

        return response()->json($data, $data['code']);
    }

    public function monitoringTicket($id)
    {
        $data = $this->assignment->monitoringTicket($id);

        return response()->json($data, $data['code']);
    }

    public function endupTicket($id)
    {
        $data = $this->assignment->endupTicket($id);

        return response()->json($data, $data['code']);
    }

    public function reactivateTicket(Request $request, $id)
    {
        $data = $this->assignment->reactivate($request, $id);

        return response()->json($data, $data['code']);
    }

    public function finishedTicket(Request $request, $id)
    {
        $data = $this->assignment->finished($request, $id);

        return response()->json($data, $data['code']);
    }
}
