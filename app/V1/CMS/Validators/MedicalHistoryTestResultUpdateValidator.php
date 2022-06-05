<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class MedicalHistoryTestResultUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [

            'medical_history_id' => '|exists:test_result,id,deleted_at,NULL',
//            'description' => 'required'
        ];
    }


    protected function attributes()
    {
        return [

//            'health_record_book_id' => Message::get("health_record_book_id"),
//            'prescription_id' => Message::get("prescription_id"),
//            'symptom' => Message::get("symptom")
        ];
    }
}