<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MembershipPackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_training_sessions' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,expired',
        ];
    }
}
