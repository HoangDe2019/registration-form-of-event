<?php
/**
 * User: Administrator
 * Date: 29/09/2018
 * Time: 12:53 AM
 */

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;

class LandUpsertValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'exists:lands,id,deleted_at,NULL',
            'name' => 'required|max:50',
            'code' => 'max:10',
        ];
    }

    protected function attributes()
    {
        return [

        ];
    }
}