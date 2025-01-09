<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class TrainingSessionController extends Controller
{
    public function show(TrainingSession $trainingSession)
    {
        return response()->json(['status' => $trainingSession->status]);
    }
}
