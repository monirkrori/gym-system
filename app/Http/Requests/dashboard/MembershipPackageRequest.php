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
            'max_training_sessions' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,expired',
        ];
    }
}
