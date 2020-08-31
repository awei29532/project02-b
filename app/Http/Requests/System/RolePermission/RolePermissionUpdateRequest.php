<?php

namespace App\Http\Requests\System\RolePermission;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class RolePermissionUpdateRequest extends FormRequest
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
            'id' => 'required|exists:roles,id',
            'permissionids' => [
                'required',
                function ($attribute, $value, $fail) {
                    if(preg_match('/,/i', $value))
                        $value = explode(',', $value);
                    collect($value)->map(function ($item) use ($fail) {
                        if (!Permission::find($item))
                            $fail('422026');
                    });
                }
            ]
        ];

        return $rules;
    }

    /**
     * 擴充路由參數 id 進入檢查
     *
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['id'] = $this->route('role');
        return $data;
    }
}
