<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        $categories = Category::all();

        if ($categories):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'categories' => $categories,
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
                $create = Category::create($request->all());
                $data = array(
                    'status' => 'success',
                    'message' => 'La categoría se ha creado correctamente',
                    'code' => 200,
                    'category' => $create,
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

    public function show($id)
    {
        $categoryId = Category::find($id);

        if ($categoryId):
            $data = array(
                'status' => 'success',
                'code' => 200,
                'category' => $categoryId,
            );
        else:
            $data = array(
                'status' => 'warning',
                'code' => 404,
                "message" => 'No existe la categoría',
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

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
                $category->update($request->all());
                $data = array(
                    'status' => 'success',
                    'message' => 'La categoría se ha editado correctamente',
                    'code' => 200,
                    'category' => $category,
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
        $categoryId = Category::find($id);
        $categoryId->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Se ha eliminado la categoría correctamente',
        ], 200);
    }
}
