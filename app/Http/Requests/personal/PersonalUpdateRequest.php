<?php

namespace App\Http\Requests\personal;

use Illuminate\Foundation\Http\FormRequest;

class PersonalUpdateRequest extends FormRequest
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
            'nickname' => 'nullable|string|max:16',
            'image' => 'nullable|image',
        ];
    }
}
