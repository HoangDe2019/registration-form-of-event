<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/12/2019
 * Time: 11:14 AM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class WeekUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            //'code' => '|max:20|unique_create:week,code'
            'code' => '|max:10|min:3'
        ];
    }

    protected function attributes()
    {
        return [
            //'code' => Message::get("code")
        ];
    }
}