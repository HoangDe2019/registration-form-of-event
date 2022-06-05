<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class MedicalHistoryV1CreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [

            'phone' => 'required|max:12|unique_create:users,phone',
            'email' => 'nullable|unique_create:users,email',
            'username' => 'nullable|unique_create:users,username',
            //'health_record_book_id' => '|exists:health_record_books,id,deleted_at,NULL',
            'user_id' => '|exists:users,id,deleted_at,NULL',
            'symptom' => 'required'
        ];
    }


    protected function attributes()
    {
        return [
            'phone' => Message::get("phone"),
            'email' => Message::get("email"),
            'username' => Message::get("username"),
//         'health_record_book_id' => Message::get("health_record_book_id"),
////            'user_id' => Message::get("user_id"),
////            'symptom' => Message::get("symptom")
        ];
    }
}