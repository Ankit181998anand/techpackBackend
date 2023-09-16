<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Helpers\Helper;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        // Check if the email or password validation failed
        if ($errors->has('email') || $errors->has('password')) {
            // Return a custom error message for email or password mismatch
            return Helper::sendError('Email or password does not match.', $errors, 422);
        }

        // For other validation errors, return the standard validation error response
        return Helper::sendError('Validation error', $errors, 422);
    }
}
