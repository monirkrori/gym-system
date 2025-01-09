<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeMealPlanRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to subscribe
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id', // User must exist
            'meal_plan_id' => 'required|exists:meal_plans,id', // Meal plan must exist
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'The user ID is required.',
            'meal_plan_id.required' => 'The meal plan ID is required.',
            'user_id.exists' => 'The user does not exist.',
            'meal_plan_id.exists' => 'The meal plan does not exist.',
        ];
    }
}
