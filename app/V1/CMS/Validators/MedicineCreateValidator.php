<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class MedicineCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [

            'medicine_origin_id' => 'required|exists:medicine_origin,id,deleted_at,NULL',
            'code' => 'required|max:20|unique_create:medicines,code',
            'name' => 'required|',
            'effect' => 'required|'
        ];
    }


    protected function attributes()
    {
        return [
            'code' => Message::get("code"),
            // 'name' => Message::get("name"),
        ];
    }
}