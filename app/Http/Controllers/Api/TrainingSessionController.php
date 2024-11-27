<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class TrainingSessionController extends Controller
{
    public function show($id)
    {
        // Find the session by ID
        $session = TrainingSession::find($id);

        if (!$session) {
            return response()->json(['message' => 'Training session not found'], 404);
        }

        return response()->json(['status' => $session->status]);
    }
}
