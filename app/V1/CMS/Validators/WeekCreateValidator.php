<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class WeekCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'code' => 'required|unique_create:week,code'
        ];
    }


    protected function attributes()
    {
        return [
            'code' => Message::get("code"),
        ];
    }
}