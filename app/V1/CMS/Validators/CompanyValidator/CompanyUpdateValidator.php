<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Validators\CompanyValidator;

use App\Company;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use Illuminate\Http\Request;

class CompanyUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'required|exists:companies,id,deleted_at,NULL',
            'code' => 'required|max:20|unique_update:companies,code',
            'email' => 'required||unique_update:companies,email',
            'name' => 'max:100',
            'tax' => 'min:10|max:14',
            'phone' => 'required|max:12'
        ];
    }

    protected function attributes()
    {
        return [
            'code' => Message::get("code"),
            'name' => Message::get("name"),
            'email' => Message::get("email"),
            'address' => Message::get("address"),
            'tax' => Message::get("tax"),
            'phone' => Message::get("phone")
        ];
    }
}
