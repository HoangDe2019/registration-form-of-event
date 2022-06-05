<?php


namespace App\V1\CMS\Validators;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class TimeOfDayValidator extends ValidatorBase
{

    protected function rules()
    {
        // TODO: Implement rules() method.
        return [
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
        ];
    }

    protected function attributes()
    {
        return [
            'start_time' => Message::get("name"),
            'end_time' => Message::get("code")
        ];
    }
}