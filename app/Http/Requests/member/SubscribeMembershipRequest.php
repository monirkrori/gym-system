<?php

namespace App\Http\Requests\member;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeMembershipRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users to subscribe
    }

    public function rules()
    {
        return [
            'membership_package_id' => 'required|exists:membership_packages,id',
        ];
    }

    public function messages()
    {
        return [
            'membership_package_id.required' => 'The membership package is required.',
            'membership_package_id.exists' => 'The selected membership package is invalid.',
        ];
    }
}
