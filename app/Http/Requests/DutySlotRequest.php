<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class DutySlotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to true to allow the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slot_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'threshold_time' => 'required|date_format:H:i|after:start_time',
            'end_time' => 'required|date_format:H:i|after:start_time|after:threshold_time',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        // Get the first validation error message
        $errorMessage = $errors->first() ?: 'Validation error occurred';

        // Redirect back with the custom error message
        throw new ValidationException($validator, back()->with('error', $errorMessage));
    }
}
