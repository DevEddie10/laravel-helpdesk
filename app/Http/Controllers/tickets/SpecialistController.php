<?php

namespace App\Http\Controllers\tickets;

use Illuminate\Http\Request;
use App\Repositories\Specialists;
use App\Http\Controllers\Controller;

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

    public function store(Request $request)
    {
        $data = $this->specialist->createComment($request);

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $data = $this->specialist->getComments($id);

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id)
    {
        $data = $this->specialist->saveTicket($request, $id);

        return response()->json($data, $data['code']);
    }

    public function reasignTicket(Request $request, $id)
    {
        $data = $this->specialist->reassigned($request, $id);

        return response()->json($data, $data['code']);
    }
}
