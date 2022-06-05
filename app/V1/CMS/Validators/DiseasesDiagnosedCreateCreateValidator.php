<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class DiseasesDiagnosedCreateCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
           'diseases_id' => 'required|exists:diseases,id,deleted_at,NULL',
         /*   'name' => 'required|unique_create:diseases,name',
            'code' => 'required|max:20|unique_create:diseases,code'*/
        ];
    }


    protected function attributes()
    {
        return [
            'diseases_id' => Message::get("diseases_id")
/*            'name' => Message::get("name"),
            'code' => Message::get("code"),*/
        ];
    }
}