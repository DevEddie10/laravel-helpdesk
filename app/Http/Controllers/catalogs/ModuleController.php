<?php

namespace App\Http\Controllers\catalogs;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        $modules = Module::all();

        if ($modules):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'modules' => $modules,
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        if (!empty($request->all())):
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
                $create = Module::create($request->all());

                $data = array(
                    'status' => 'success',
                    'message' => 'Modulo creado correctamente',
                    'code' => 200,
                    'module' => $create,
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

    public function show($id)
    {
        $moduleId = Module::find($id);

        if ($moduleId):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'modulo' => $moduleId,
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
        $module = Module::find($id);

        if (!empty($request->all())):
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
                $module->update($request->all());

                $data = array(
                    'status' => 'success',
                    'message' => 'Modulo creado correctamente',
                    'code' => 200,
                    'module' => $module,
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
        $moduleId = Module::find($id);
        $moduleId->delete();

        return response()->json([
            'status' => 'success',
            'code'   => 200,
            'message' => 'Se ha eliminado el modulo correctamente'
        ], 200);
    }
}
