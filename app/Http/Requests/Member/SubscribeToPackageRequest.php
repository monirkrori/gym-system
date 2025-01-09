<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\UserMembership;

class SubscribeToPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'package_id' => [
                'required',
                'exists:membership_packages,id',
                function ($attribute, $value, $fail) {
                    // Check if the user has an active membership
                    $activeMembership = UserMembership::where('user_id', auth()->id())
                        ->where('status', 'active')
                        ->exists();

                    if (!$activeMembership) {
                        $fail('You must have an active membership to subscribe to a package.');
                    }
                },
            ],
        ];
    }
}
