<?php

namespace App\Http\Requests\member;

use Illuminate\Foundation\Http\FormRequest;

class CancelSessionRequest extends FormRequest
{/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Assuming authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'session_id' => 'required|exists:training_sessions,id',
        ];
    }
}
