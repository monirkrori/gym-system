<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:available,maintenance',
            'type'=> 'nullable|string,'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المعدة مطلوب.',
            'status.required' => 'الحالة مطلوبة.',
            'status.in' => 'الحالة يجب أن تكون جاهزة أو معطلة.',
        ];
    }
}
