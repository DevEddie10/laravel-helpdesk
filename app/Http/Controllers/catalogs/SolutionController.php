<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SolutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        $solutions = Solution::all();

        if ($solutions):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'solutions' => $solutions
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        if (!empty($request)):
            $validated = Validator::make($request->all(), [
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
                $create = Solution::create($request->all());

                $data = array(
                    'status' => 'success',
                    'message' => 'Solucion creado correctamente',
                    'code' => 200,
                    'solucion' => $create,
                );
            endif;
        else:
            $data = array(
                'status' => 'error',
                'message' => 'No se ha enviado nada al formulario',
                'code' => 404,
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $solucionId = Solution::find($id);

        if ($solucionId):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'solucion' => $solucionId,
            );
        else:
            $data = array(
                'status' => 'error',
                'message' => 'El modulo no existe',
                'code' => 404,
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id)
    {
        $solucion = Solution::find($id);

        if (!empty($request)):
            $validated = Validator::make($request->all(), [
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
                $solucion->update($request->all());

                $data = array(
                    'status' => 'success',
                    'message' => 'Solución creado correctamente',
                    'code' => 200,
                    'solucion' => $solucion,
                );
            endif;
        else:
            $data = array(
                'status' => 'error',
                'message' => 'No se ha enviando nada al formulario',
                'code' => 404,
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function destroy($id)
    {
        $solucionId = Solution::find($id);
        $solucionId->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Se ha eliminado la solucion correctamente',
        ], 200);
    }
}
