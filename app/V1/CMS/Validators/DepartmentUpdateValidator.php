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

class DepartmentUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'code' => '|unique_update:departments,code',
            'name'=>'|unique_update:departments,name'
        ];
    }

    protected function attributes()
    {
        return [
//            'company_id' => Message::get("company_id"),
            'code' => Message::get("code"),
            'name' => Message::get("name"),
        ];
    }
}