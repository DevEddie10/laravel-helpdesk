<?php

namespace App\Http\Controllers\tickets;

use App\Helpers\JwtAuth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends Controller
{
    private $token;

    public function __construct()
    {
        $this->middleware('api.auth')->except('show');
        $this->token = request()->header('Authorization');
    }

    public function index()
    {
        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        $user = User::find($decoded->sub);

        $data = [
            'status' => 'success',
            'code' => 200,
            'notifications' => $user->unreadNotifications,
        ];

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user):
            $data = [
                'status' => 'success',
                'code' => 200,
                'notifications' => $user->unreadNotifications,
            ];
        endif;

        return response()->json($data, $data['code']);
    }

    public function markAsRead($id)
    {
        DatabaseNotification::find($id)->markAsRead();

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function markAllasRead($id)
    {
        $user = User::find($id);

        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'notifications' => $user
        ], 200);
    }
}
