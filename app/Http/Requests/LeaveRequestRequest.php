<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class LeaveRequestRequest extends FormRequest
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

        // Leave Request Information
        $rules['requisition_type'] = 'required|exists:requisition_types,id';
        $rules['leave_type'] = 'required|exists:leave_types,id';
        $rules['start_date'] = 'required|date|before_or_equal:end_date';
        $rules['end_date'] = 'required|date|after_or_equal:start_date';
        $rules['reliever_emp_id'] = 'nullable|exists:employees,emp_id';
        $rules['reason'] = 'required|string|max:500';
        $rules['remarks'] = 'nullable|string|max:500'; // Only if the role is super-admin or hr
        $rules['attachments.*'] = 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png,gif,bmp,webp';

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
