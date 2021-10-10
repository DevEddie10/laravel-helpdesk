<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\Assign;

class ReportController extends Controller
{
    public function show(int $id)
    {
        $user = Assign::with([
            'user', 'specialist', 'category', 'medio', 'state',
            'module', 'solution', 'commentaries.specialist',
        ])
            ->orWhere('user_id', $id)
            ->orWhere('assigned_id', $id)
            ->get();

        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 201);
    }
}