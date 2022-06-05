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

class MedicineOriginUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            // 'id' => 'required|exists:medicine_origin,id,deleted_at,NULL',
            'name' => 'required|unique_create:medicine_origin,name'
        ];
    }

    protected function attributes()
    {
        return [
            'name' => Message::get("name"),
        ];
    }
}