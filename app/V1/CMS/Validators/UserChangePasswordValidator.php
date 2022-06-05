<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/24/2019
 * Time: 10:42 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class UserChangePasswordValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'password' => 'required|min:8',
            'new_password' => 'bail|required|min:8',
            'password_new_confirmation' => 'bail|required|same:new_password',
        ];
    }

    protected function attributes()
    {
        return [
            'password' => Message::get("password"),
            'new_password' => Message::get("new_password"),
            'password_new_confirmation' => Message::get("password_new_confirmation"),
        ];
    }
}