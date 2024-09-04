<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class NoticeBoardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Allow all users for now, adjust as needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');
        return [
            'notice_type' => 'required|string|max:255',
            'notice_date' => 'required|date',
            'notice_file' => 'nullable|file|max:5120',
            'notice_by' => 'required|string|max:255',
            'notice_description' => 'required|string',
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
