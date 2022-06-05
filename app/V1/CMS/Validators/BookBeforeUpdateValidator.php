<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class BookBeforeUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
//            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'medical_schedule_id' => 'required|exists:medical_schedules,medical_schedule_id,deleted_at,NULL',
            'time' => 'required|unique_create:book_before,time',
            'name' => 'required'
        ];
    }


    protected function attributes()
    {
        return [
//            'user_id' => Message::get("user_id"),
            'medical_schedule_id' => Message::get("medical_schedule_id"),
            'name' => Message::get("name"),
            'time'=>Message::get('time')
        ];
    }
}