<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {   ;
        return [
            'name_en'=>['required','string','max:255'],
            'name_ar'=>['required','string','max:255'],
            'price'=>['required','numeric','min:1','max:50000'],
            'amount'=>['required','integer','min:1'],
             'code'=>['required','string','max:10','unique:products,code,'.request()->route('product')],
             'cond'=>['required','string',Rule::in(['new', 'ordinary'])],
             'status'=>['required','integer',Rule::in([0, 1])],
             'brand_id'=>['nullable','integer','exists:brands,id'],
             'subcategory_id'=>['required','integer','exists:subcategories,id'],
             'details_en'=>['nullable','string','max:100'],
             'details_ar'=>['nullable','string','max:100'],
             'image'=>['nullable','max:1000', 'mimes:jpg,bmp,png']
        ];
    }
}
