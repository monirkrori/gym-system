<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users
    }

    public function rules()
    {
        return [
            'training_session_id' => 'required|exists:training_sessions,id', // Valid training session
            'check_in' => 'required|date|before_or_equal:now', // Check-in time should be now or before
            'check_out' => 'nullable|date|after:check_in', // Check-out time, must be after check-in if exists
            'status' => 'required|in:present,absent', // Attendance status
            'notes' => 'nullable|string|max:255', // Optional notes
        ];
    }

    public function messages()
    {
        return [
            'training_session_id.required' => 'The training session ID is required.',
            'check_in.required' => 'The check-in time is required.',
            'check_out.after' => 'Check-out time must be after check-in time.',
        ];
    }
}
