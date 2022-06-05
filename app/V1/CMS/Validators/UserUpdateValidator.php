<?php
/**
 * User: Administrator
 * Date: 14/10/2018
 * Time: 01:30 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use App\User;
use Illuminate\Http\Request;

class UserUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'required|exists:users,id,deleted_at,NULL',
            'email' => 'nullable|unique_update:users,email',
            'code' => 'nullable|max:50|unique_update:users,code',
            'password' => 'nullable|min:8',
            'phone' => 'max:12',
        ];
    }

    protected function attributes()
    {
        return [
            'phone' => Message::get("phone"),
            'email' => Message::get("email"),
            'code' => Message::get("code"),
            'password' => Message::get("password"),
        ];
    }
}
