<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class PrescriptionUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'medicine_id' => '|exists:medicines,id,deleted_at,NULL',
          //  'week_id' => 'required|exists:week,id,deleted_at,NULL',
         //   'session' => 'required'
        ];
    }


    protected function attributes()
    {
        return [
           'medicine_id' => Message::get("medicine_id"),
           // 'week_id' => Message::get("week_id"),
       //     'session' => Message::get("session"),
        ];
    }
}