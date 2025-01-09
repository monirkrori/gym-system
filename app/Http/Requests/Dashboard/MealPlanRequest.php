<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MealPlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plan_id' => 'nullable|exists:membership_plans,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'calories_per_day' => 'required|integer|min:0',
        ];
    }
}
