<?php

namespace App\Models\Traits;

trait ApiResponseTrait
{
    // استجابة النجاح
    public function successResponse($data, $message = 'Operation successful', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    // استجابة الخطأ
    public function errorResponse($message = 'Operation failed', $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $statusCode);
    }
}
