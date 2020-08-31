<?php

namespace App\Http\Requests\Account\Agent;

use Illuminate\Foundation\Http\FormRequest;

class AgentGameToggleEnabledRequest extends FormRequest
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
            'agent_id' => 'required|exists:agent,id',
            'game_id' => 'required|exists:game,id',
            'status' => 'required|in:0,1',
        ];
    }
}
