<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class MyRequest extends FormRequest
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
        $requestType = $this->input('a'); // Assuming 'a' is used to determine the request type

        switch ($requestType) {
            case 'late-arrival':
                $rules = [
                    'date' => 'required|date',
                    'time' => 'required|date_format:H:i',
                    'late_reason' => 'required|string',
                    'late_note' => 'required|string',
                ];
                break;

            case 'early-exit':
                $rules = [
                    'early_exit_date' => 'required|date',
                    'early_exit_time' => 'required|date_format:H:i',
                    'early_exit_reason' => 'required|string',
                    'early_exit_note' => 'required|string',
                ];
                break;

            case 'home-office':
                $rules = [
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'home_office_reason' => 'required|string',
                    'home_office_note' => 'required|string',
                ];
                break;

            case 'time-track':
                $rules = [
                    'type' => 'required|in:start,stop,status',
                    'duration' => 'nullable|numeric',
                ];
                break;

            default:
                $rules['a'] = 'required|in:late-arrival,early-exit,home-office,time-track';
                break;
        }

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
        throw new ValidationException($validator, back()->with('error', $errorMessage));
    }
}
