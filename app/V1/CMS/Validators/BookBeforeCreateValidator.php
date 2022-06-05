<?php

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class BookBeforeCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'user_id' => '|exists:users,id,deleted_at,NULL',
            'medical_schedule_id' => 'required|exists:medical_schedules,id,deleted_at,NULL',
            'name' => 'required',
            'options_time'=>'required|exists:times_of_day,id,deleted_at,NULL'
        ];
    }


    protected function attributes()
    {
        return [
            'user_id' => Message::get("user_id"),
            'medical_schedule_id' => Message::get("medical_schedule_id"),
            'name' => Message::get("name"),
            'options_time' => Message::get("options_time"),
        ];
    }
}