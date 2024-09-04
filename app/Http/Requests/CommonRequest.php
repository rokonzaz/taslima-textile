<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CommonRequest extends FormRequest
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
        $id = $this->route('id');
        $rules = [];
        // Conditional rule for weekends[]
        if ($this->has('weekends')) {
            $rules['weekends'] = 'required|array|min:2'; // Ensure weekends is an array and has at least one element
            $rules['weekends.*'] = 'string'; // Each weekend value should be an integer and exist in the weekends table
        }else if ($this->has('device_ip')) {
            $rules['device_ip'] = 'required|ip';
        } else {
            // Rules for other fields
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                'unique:departments,name,' . $id,
                'unique:designations,name,' . $id,
                'unique:document_type,name,' . $id,
                'unique:roles,name,' . $id,
                'unique:users,name,' . $id
            ];

            $rules['email'] = 'nullable|email|unique:users,email,' . $id;
            $rules['is_active'] = 'nullable|string';
            $rules['status'] = 'nullable|string';
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
