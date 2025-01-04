<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UserMemberShipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:membership_plans,id',
            'package_id' => 'nullable|exists:membership_packages,id',
            'start_date' => 'required|date',
            'status' => 'required|in:active,expired,cancelled',
        ];
    }

}
