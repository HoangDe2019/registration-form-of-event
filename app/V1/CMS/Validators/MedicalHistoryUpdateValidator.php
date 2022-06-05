<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class MedicalHistoryUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [

            'health_record_book_id' => '|exists:health_record_books,id,deleted_at,NULL',
            'prescription_id' => '|exists:prescriptions,id,deleted_at,NULL|unique_create:medical_histories,prescription_id',
            'user_id' => '|exists:users,id,deleted_at,NULL',
//            'description' => 'required'
        ];
    }


    protected function attributes()
    {
        return [

            'health_record_book_id' => Message::get("health_record_book_id"),
            'prescription_id' => Message::get("prescription_id"),
//            'symptom' => Message::get("symptom")
        ];
    }
}