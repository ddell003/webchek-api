<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
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
    {
        $rules = [
            'name'=>'required',
            'app_id'=>'required',
            'url'=>'nullable',
            'curl'=>'nullable',
            'frequency'=>'required',
            'frequency_amount'=>'required'
        ];

        return $rules;
    }
}
