<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainerRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('create-trainer');
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
            'rating_avg' => 'nullable|numeric|min:0|max:5',
        ];
    }
}
