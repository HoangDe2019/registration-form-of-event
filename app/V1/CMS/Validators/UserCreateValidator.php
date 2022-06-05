<?php

/**
 * User: Administrator
 * Date: 28/09/2018
 * Time: 09:35 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use App\User;
use Illuminate\Http\Request;

class UserCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'email' => 'nullable|unique_create:users,email',
            'username' => 'nullable|unique_create:users,username',
         /*   'code' => 'required|max:50|unique_create:users,code',*/
            'phone' => 'required|max:12|unique_create:users,phone',
            'password' => 'required',
        ];
    }

    protected function attributes()
    {
        return [
            'phone' => Message::get("phone"),
            'email' => Message::get("email"),
            'username' => Message::get("username"),
/*            'code' => Message::get("code"),*/
            'password' => Message::get("password"),
        ];
    }
}
