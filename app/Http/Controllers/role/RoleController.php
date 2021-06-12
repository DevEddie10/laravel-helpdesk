<?php

namespace App\Http\Controllers\role;

use App\Helpers\JwtAuth;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    private $token;

    public function __construct()
    {
        $this->middleware('api.auth');
        $this->token = request()->header('Authorization');
    }

    public function index()
    {
        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        $roles = Role::where([
            'id' => 3,
        ])->with('user', function ($query) use ($decoded) {
            return $query->where('id', '!=', $decoded->sub);
        })->get();

        if ($roles):
            $data = [
                'status' => 'success',
                'code' => 200,
                'roles' => $roles,
            ];
        endif;

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        if ($validated->fails()):
            $data = array(
                'status' => 'warning',
                'code' => 404,
                'errors' => $validated->errors(),
            );
        else:
            $result = Role::create($request->all());

            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Se ha creado Role',
                'role' => $result,
            );
        endif;

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $role = Role::find($id);

        if ($role):
            $data = [
                'status' => 'success',
                'code' => 200,
                'role' => $role,
            ];
        else:
            $data = [
                'status' => 'error',
                'message' => 'El role no existe',
                'code' => 404,
            ];
        endif;

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        if ($validated->fails()) {
            $data = array(
                'status' => 'warning',
                'code' => 404,
                'errors' => $validated->errors(),
            );
        } else {
            $role->update($request->all());

            $data = array(
                'status' => 'success',
                'code' => 200,
                'role' => $role,
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if ($role):
            $role->delete();

            $data = array(
                'status' => 'success',
                'message' => 'Role eliminado correctamente',
                'code' => 200,
            );
        else:
            $data = array(
                'status' => 'error',
                'message' => 'El role no existe',
                'code' => 404,
            );
        endif;

        return response()->json($data, $data['code']);
    }
}
