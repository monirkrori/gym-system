<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MembershipPlanRequest extends FormRequest
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
            'duration_month' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,expired',
        ];
    }
}
