<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDateRequest extends FormRequest
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
        return [
            'day_number' => 'required|min:1|max:31',
            'month_number' => 'required|min:1|max:31',
            'year_number' => 'required|min:1|max:31',
            'term' => 'required'
        ];
    }
}
