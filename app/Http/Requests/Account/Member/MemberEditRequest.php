<?php

namespace App\Http\Requests\Account\Member;

use Illuminate\Foundation\Http\FormRequest;

class MemberEditRequest extends FormRequest
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
        $id = $this->route('member');
        return [
            'id' => 'required|exists:member,id',
            'level_id' => 'required|exists:level,id',
            'cell_phone' => "nullable|string|unique:member,cell_phone,$id",
            'country' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'line' => 'nullable|string',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('member');
        return $data;
    }
}
