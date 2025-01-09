<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('edit-trainer');
    }

    public function rules(): array
    {
        return [
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
            'rating_avg' => 'nullable|numeric|min:0|max:5',
        ];
    }
}

