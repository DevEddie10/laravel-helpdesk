<?php

namespace App\Http\Controllers\login;

use App\Models\User;
use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth')->only('index');
    }

    public function store(Request $request)
    {
        $jwtAuth = new JwtAuth();

        if (!empty($request->all())):
            $validated = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validated->fails()):
                $data = array(
                    'status' => 'warning',
                    'code' => 404,
                    'errors' => $validated->errors(),
                );
            else:
                $user = User::where([
                    'email' => $request->email,
                ])->first();

                if (!$user):
                    $data = array(
                        'status' => 'info',
                        'message' => 'Email incorrecto',
                        'code' => 404
                    );

                    return response()->json($data, $data['code']);
                endif;

                if (Hash::check($request->password, $user->password)):
                    $data = array(
                        'token' => $jwtAuth->singup($user),
                        'code' => 200
                    );
                else:
                    $data = array(
                        'status' => 'info',
                        'message' => 'Contraseña incorrecta',
                        'code' => 404
                    );
                endif;
            endif;
        else:
            $data = array(
                'success' => 'error',
                'message' => 'No se ha enviado nada al formulario',
                'code' => 404
            );
        endif;

        return response()->json($data, $data['code']);
    }
}
