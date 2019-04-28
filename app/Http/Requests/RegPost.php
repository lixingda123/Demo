<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegPost extends FormRequest
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
            'user_email'=>'required|unique:shop_user',
            'user_pwd'=>'required',
            'repwd'=>'sometimes|same:user_pwd'
        ];
    }
    public function messages(){
     return [
     'user_email.required' => '邮箱不能为空',
     'user_email.unique' => '邮箱已注册',
     'user_pwd.required' => '密码不能为空',
     'repwd.same' => '确认密码要和密码一致',
     ];
}
}
