<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        $users = User::orderBy('id', 'desc')
            ->with('roles')->get();

        if ($users):
            $data = [
                'status' => 'success',
                'code' => 200,
                'users' => $users,
            ];
        endif;

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        if (!empty($request->all())):
            $validate = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:50'],
                'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
                //'password' => ['required', 'string', 'min:6'],
            ]);

            if ($validate->fails()):
                $data = [
                    'status' => 'info',
                    'code' => 404,
                    'errors' => $validate->errors(),
                ];
            else:
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make('admin_23'),
                ]);

                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Usuario creado correctamente.',
                    'user' => $user,
                ];
            endif;
        else:
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'El formulario esta vacio.',
            ];
        endif;

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $user = User::with('roles')->find($id);

        if ($user):
            $data = [
                'status' => 'success',
                'code' => 200,
                'user' => $user,
            ];
        else:
            $data = [
                'status' => 'error',
                'message' => 'No se encontro el usuario',
                'code' => 404,
            ];
        endif;

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $data = [
            'status' => 'success',
            'message' => 'Usuario editado correctamente',
            'code' => 200,
            'user' => $user
        ];

        return response()->json($data, $data['code']);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user):
            $user->delete();

            $data = [
                'status' => 'success',
                'message' => 'Usuario eliminado correctamente',
                'code' => 200,
            ];
        else:
            $data = [
                'status' => 'error',
                'message' => 'El usuario no existe',
                'code' => 404,
            ];
        endif;

        return response()->json($data, $data['code']);
    }
}
