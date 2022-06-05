<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class MedicalHistoryCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [

          'health_record_book_id' => 'required|exists:health_record_books,id,deleted_at,NULL',
            'user_id' => '|exists:users,id,deleted_at,NULL',
            'symptom' => 'required'
        ];
    }


    protected function attributes()
    {
        return [

//         'health_record_book_id' => Message::get("health_record_book_id"),
////            'user_id' => Message::get("user_id"),
////            'symptom' => Message::get("symptom")
        ];
    }
}