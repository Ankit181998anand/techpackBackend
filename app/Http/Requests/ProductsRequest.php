<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Helpers\Helper;

class ProductsRequest extends FormRequest
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
            //
            'productSku'=>'required',
            'productSlug'=>'required',
            'productName'=>'required|unique:Products,product_name',
            'metaDesc'=>'required',
            'metaKeyword'=>'required',
            'shortDescription'=>'required',
            'longDescription'=>'required',
            'addiInfo'=>'required',
            'productPrice'=>'required',
            'catId'=>'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        // send error message
        Helper::sendError('validation error',$validator->errors(),403);
    }
}
