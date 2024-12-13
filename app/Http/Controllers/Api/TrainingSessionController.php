<?php

namespace App\Http\Controllers\Api;

use App\Models\TrainingSession;
use App\Http\Controllers\Controller;

class TrainingSessionController extends Controller
{

    public function show($id)
    {
        // Find the session by ID
        $session = TrainingSession::findOrFail($id);

        if (!$session) {
            return response()->json(['message' => 'Training session not found'], 404);
        }

        return response()->json(['status' => $session->status]);
    }
}
