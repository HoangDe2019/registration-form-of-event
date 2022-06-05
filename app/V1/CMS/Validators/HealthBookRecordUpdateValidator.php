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

class HealthBookRecordUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
//            'company_id' => 'required|exists:companies,id,deleted_at,NULL',
//            'active_at' => 'required|'
        ];
    }

    protected function attributes()
    {
        return [
//            'company_id' => Message::get("company_id"),
//            'active_at' => Message::get("code"),
            // 'name' => Message::get("name"),
        ];
    }
}