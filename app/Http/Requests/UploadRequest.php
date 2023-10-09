<?php

namespace App\Http\Requests;

use App\Http\Helpers\Helper;
use Aws\Api\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'zipFile' => 'file|mimes:zip'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        // send error message
        Helper::sendError('validation error',$validator->errors(),403);
    }
}
