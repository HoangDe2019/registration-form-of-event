<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class DepartmentCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
        /*    'company_id' => 'required|exists:companies,id,deleted_at,NULL',*/
            'name' => 'required|unique_create:departments,name',
            'code' => 'required||unique_create:departments,code'
        ];
    }


    protected function attributes()
    {
        return [
       /*     'company_id' => Message::get("company_id"),*/
            'name' => Message::get("name"),
            'code' => Message::get("code"),
        ];
    }
}