<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class TrainingSessionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'package_id' => 'required|exists:membership_packages,id',
            'trainer_id' => 'required|exists:trainers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:group,personal',
            'status' => 'required|in:active,expired,scheduled',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_capacity' => 'required|integer|min:1',
        ];
    }
}
