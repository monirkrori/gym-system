<?php

namespace App\Http\Requests\member;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rateable_id' => 'required|integer|exists:trainers,id',
            'rateable_type' => 'required|string|in:trainer,service',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ];
    }
}
