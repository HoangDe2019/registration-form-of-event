<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/12/2019
 * Time: 11:14 AM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class MedicalScheduleUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
           'week_id' => 'required|exists:week,id,deleted_at,NULL',
        ];
    }

    protected function attributes()
    {
        return [
//            'user_id' => Message::get("user_id"),
            'week_id' => Message::get("week_id"),
            'session' => Message::get("session"),
        ];
    }
}