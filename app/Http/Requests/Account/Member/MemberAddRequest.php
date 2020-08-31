<?php

namespace App\Http\Requests\Account\Member;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class MemberAddRequest extends FormRequest
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
            'username' => 'required|string|unique:member,username|min:6|max:10',
            'level_id' => 'required|exists:level,id',
            'rebate_id' => 'nullable|exists:rebate,id',
            'cell_phone' => 'nullable|string',
            'birthday' => 'nullable|date_format:"Y-m-d"',
            'country' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'line' => 'nullable|string',
        ];

        /** @var User */
        $user = auth()->user();
        if (!$user->isAgent()) {
            $rules['agent_id'] = 'required|exists:agent,id';
        }

        return $rules;
    }
}
