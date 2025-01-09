<?php

namespace App\Http\Requests\Member;

use App\Models\UserMembership;
use Illuminate\Foundation\Http\FormRequest;

class SubscribeToMembershipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only authenticated users can subscribe
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'plan_id' => [
                'required',
                'exists:membership_plans,id',
                function ($attribute, $value, $fail) {
                    // Check if the user already has an active membership for this plan
                    $existingMembership = UserMembership::where('user_id', auth()->id())
                        ->where('plan_id', $value)
                        ->where('status', 'active')
                        ->exists();

                    if ($existingMembership) {
                        $fail('You already have an active subscription for this plan.');
                    }
                },
            ],
            'package_id' => 'nullable|exists:membership_packages,id',
        ];
    }
}
