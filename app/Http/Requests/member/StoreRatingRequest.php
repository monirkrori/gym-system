<?php

namespace App\Http\Requests\member;


use App\Models\Trainer;
use App\Models\TrainingSession;
use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
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
            'rateable_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    // Check if the rateable_id exists in the appropriate table
                    $rateableType = $this->input('rateable_type');
                    if ($rateableType === 'trainer') {
                        if (!Trainer::where('id', $value)->exists()) {
                            $fail('The selected trainer does not exist.');
                        }
                    } elseif ($rateableType === 'session') {
                        if (!TrainingSession::where('id', $value)->exists()) {
                            $fail('The selected training session does not exist.');
                        }
                    } else {
                        $fail('Invalid rateable type.');
                    }
                },
            ],
            'rateable_type' => 'required|string|in:trainer,session',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ];
    }
}
