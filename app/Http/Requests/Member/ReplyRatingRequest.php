<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class ReplyRatingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users to reply to ratings
    }

    public function rules()
    {
        return [
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ];
    }
}
