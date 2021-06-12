<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        $states = State::all();

        if ($states):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'states' => $states,
            );

        endif;

        return response()->json($data, $data['code']);
    }

    public function store()
    {
        $params = $this->getPost();

        if (!empty($params)):
            $validated = Validator::make($params, [
                'name' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);

            if ($validated->fails()):
                $data = array(
                    'status' => 'warning',
                    'code' => 404,
                    'errors' => $validated->errors(),
                );
            else:
                $create = State::create($params);
                $data = array(
                    'status' => 'success',
                    'message' => 'Estado creado correctamente.',
                    'code' => 200,
                    'state' => $create,
                );
            endif;
        else:
            $data = array(
                'status' => 'error',
                'message' => 'No se han enviado datos al formulario.',
                'code' => 404,
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $stateId = State::find($id, ['id', 'name', 'description']);

        if ($stateId):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'staet' => $stateId,
            );
        else:
            $data = array(
                'status' => 'warning',
                'code' => 404,
                "message" => 'No existe el estado',
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function update($id)
    {
        $params = $this->getPost();
        $state = State::find($id);

        if (!empty($params)):
            $validated = Validator::make($params, [
                'name' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);

            if ($validated->fails()):
                $data = array(
                    'status' => 'warning',
                    'code' => 404,
                    'errors' => $validated->errors(),
                );
            else:
                $state->update($params);
                $data = array(
                    'status' => 'success',
                    'message' => 'Estado editado correctamente',
                    'code' => 200,
                    'state' => $state,
                );
            endif;
        else:
            $data = array(
                'status' => 'warning',
                'code' => 404,
                "message" => 'No hay enviado nada al formulario',
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function destroy($id)
    {
        $mediaId = State::find($id);
        $mediaId->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Se ha eliminado correctamente',
        ], 200);
    }

    public function allPriorityState($id)
    {
        $priorities = State::where([
            'status' => $id,
        ])->get(['id', 'name']);

        if ($priorities):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'priorities' => $priorities,
            );

        endif;

        return response()->json($data, $data['code']);
    }

    private function getPost()
    {
        $json = file_get_contents("php://input", null);
        return json_decode($json, true);
    }
}
