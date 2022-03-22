<?php

namespace App\Helpers;

use DomainException;
use Firebase\JWT\JWT;
use UnexpectedValueException;

class JwtAuth
{
    private $key;

    public function __construct()
    {
        $this->key = 'asdfgh_jklñqw_ertyui_opzxcv_bnmewq';
    }

    public function singup($user, $getToken = null)
    {
        $signup = false;

        if (is_object($user)):
            $signup = true;
        endif;

        if ($signup):
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60),
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            if (is_null($getToken)):
                $data = $jwt;
            else:
                $data = $decoded;
            endif;
        else:
            $data = [
                'status' => 'error',
                'message' => 'Login incorrecto'
            ];
        endif;

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (UnexpectedValueException $e) {
            $auth = false;
        } catch (DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)):
            $auth = true;
        else:
            $auth = false;
        endif;

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }
}