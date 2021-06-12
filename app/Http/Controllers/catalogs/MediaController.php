<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        $medios = Media::all();

        if ($medios):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'medios' => $medios,
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
            ]);

            if ($validated->fails()):
                $data = array(
                    'status' => 'warning',
                    'code' => 404,
                    'errors' => $validated->errors(),
                );
            else:
                $create = Media::create($request->all());

                $data = array(
                    'status' => 'success',
                    'message' => 'Medio creado correctamente.',
                    'code' => 200,
                    'media' => $create,
                );
            endif;
        else:
            $data = array(
                'status' => 'error',
                'message' => 'No se han enviado datos al formulario.',
                'code' => 200,
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $mediaId = Media::find($id);

        if ($mediaId):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'media' => $mediaId,
            );
        else:
            $data = array(
                'status' => 'warning',
                'code' => 404,
                'message' => 'No existe el medio',
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id)
    {
        $media = Media::find($id);

        if (!empty($request)):
            $validated = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
            ]);

            if ($validated->fails()):
                $data = array(
                    'status' => 'warning',
                    'message' => 'Todos los campos son obligatorios',
                    'code' => 404,
                    'errors' => $validated->errors(),
                );
            else:
                $media->update($request->all());
                $data = array(
                    'status' => 'success',
                    'message' => 'Medio editado correctamente',
                    'code' => 200,
                    'medio' => $media,
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
        $mediaId = Media::find($id);
        $mediaId->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Se ha eliminado el medio correctamente',
        ], 200);
    }
}
