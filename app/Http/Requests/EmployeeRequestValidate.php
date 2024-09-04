<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class EmployeeRequestValidate extends FormRequest
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

        // Basic Information
        $rules['full_name'] = 'required|string|max:255';
        $rules['emp_id'] = 'required|string|unique:employees,emp_id'; // emp_id should be unique
        $rules['organization'] = 'required|exists:organizations,id';
        $rules['designation'] = 'required|exists:designations,id';
        $rules['emp_department'] = 'required|exists:departments,id';
        $rules['joining_date'] = 'required|date';
        $rules['email'] = 'required|email|unique:employees,email'; // email should be unique
        $rules['phone'] = ['required','regex:/^(\+8801[3-9]\d{8}|\+880[1-9]\d{9}|\+8801[3-9]\d{8}|01[3-9]\d{8})$/']; // Correct regex for phone number
        $rules['gender'] = 'required|in:Male,Female,Other';
        $rules['duty_slot'] = 'required|exists:duty_slots,id';

        // Additional Information
        $rules['emergency_contact'] = ['nullable','regex:/^(\+8801[3-9]\d{8}|\+880[1-9]\d{9}|\+8801[3-9]\d{8}|01[3-9]\d{8})$/']; // Correct regex for emergency contact
        $rules['birth_year'] = 'nullable|date';
        $rules['blood_group'] = 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-';
        $rules['profile_photo'] = 'nullable|mimes:jpg,jpeg,png,gif,bmp,tiff,webp|max:5120'; // 5MB
        $rules['employee_resume'] = 'nullable|mimes:pdf,docx,doc|max:5120'; // 5MB
        $rules['present_address'] = 'nullable|string|max:1000';
        $rules['permanent_address'] = 'nullable|string|max:1000';

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
