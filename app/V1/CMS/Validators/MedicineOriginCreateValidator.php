<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class MedicineOriginCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'name' => 'required|unique_create:medicine_origin,name'
        ];
    }


    protected function attributes()
    {
        return [
            'name' => Message::get("name"),
        ];
    }
}