<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class DutySlotRulesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Set to true if you want to allow all users, or implement your authorization logic here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['duty_slot_id'] = 'required|exists:duty_slots,id';
        $rules['title'] = 'required|string|max:255';
        $rules['start_date'] = 'required|date|before_or_equal:end_date';
        $rules['end_date'] = 'required|date|after_or_equal:start_date';
        $rules['start_time'] = 'required|date_format:H:i|before_or_equal:threshold_time|before:end_time';
        $rules['threshold_time'] = 'required|date_format:H:i|after_or_equal:start_time|before:end_time';
        $rules['end_time'] = 'required|date_format:H:i|after:start_time|after:threshold_time';


        return $rules;
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
        throw new ValidationException($validator, back()->with('error', $errorMessage)->withInput());
    }
}
