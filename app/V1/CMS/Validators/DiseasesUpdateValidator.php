<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class DiseasesUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'code' => 'required|unique_create:diseases,code',
            'name' => 'required|unique_create:diseases,name',
            // 'department_id' => 'required|exists:departments,id,deleted_at,NULL',
        ];
    }


    protected function attributes()
    {
        return [
            'name' => Message::get("name"),
            'code' => Message::get("code")
        ];
    }
}