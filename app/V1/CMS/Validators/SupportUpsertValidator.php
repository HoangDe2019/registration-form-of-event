<?php
/**
 * User: Administrator
 * Date: 29/09/2018
 * Time: 10:01 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;

class SupportUpsertValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'exists:supports,id,deleted_at,NULL',
            'code' => 'max:10',
            'name' => 'required|max:50',
            'type' => 'in:MANURE,PESTICIDE',
        ];
    }

    protected function attributes()
    {
        return [

        ];
    }
}