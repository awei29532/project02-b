<?php

namespace App\Http\Requests\Account\Sub;

use Illuminate\Foundation\Http\FormRequest;

class SubEditRequest extends FormRequest
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
            'id' => 'required|exists:user,id',
            'nickname' => 'nullable|string|max:16',
            // 'view_member_type' => 'required|in:0,1,2',
            // 'permission.*' => 'nullable|exists:api,id',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('sub_account');
        return $data;
    }
}
