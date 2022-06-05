<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class DiseasesCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
           'department_id' => 'required|exists:departments,id,deleted_at,NULL',
            'name' => 'required|unique_create:diseases,name',
            'code' => 'required|max:20|unique_create:diseases,code'
        ];
    }


    protected function attributes()
    {
        return [
          'department_id' => Message::get("department_id"),
            'name' => Message::get("name"),
            'code' => Message::get("code"),
        ];
    }
}