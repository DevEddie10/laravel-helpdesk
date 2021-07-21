<?php

namespace App\Http\Controllers\tickets;

use App\Repositories\Specialists;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\FinishedTicketRequest;
use App\Http\Requests\ReasignCommentRequest;
use App\Models\Assign;

class SpecialistController extends Controller
{
    protected $specialist;

    public function __construct(Specialists $specialist)
    {
        $this->middleware('api.auth');
        $this->specialist = $specialist;
    }

    public function index()
    {
        $data = $this->specialist->getTickets();

        return response()->json($data, $data['code']);
    }

    public function store(CreateCommentRequest $request)
    {
        $data = $this->specialist->createComment($request);

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $data = $this->specialist->getComments($id);

        return response()->json($data, $data['code']);
    }

    public function update(FinishedTicketRequest $request, Assign $asignacione)
    {
        $data = $this->specialist->saveTicket($request, $asignacione);

        return response()->json($data, $data['code']);
    }

    public function reasignTicket(ReasignCommentRequest $request, Assign $reasign)
    {
        $data = $this->specialist->reassigned($request, $reasign);

        return response()->json($data, $data['code']);
    }
}