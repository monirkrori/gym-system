<?php
namespace App\Http\Requests\member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,hidden,deleted',
        ];
    }
}
