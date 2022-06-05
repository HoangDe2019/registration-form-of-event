<?php
/**
 * User: Ho Sy Dai
 * Date: 10/3/2018
 * Time: 1:55 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;

/**
 * Class InfoUpsertValidator
 * @package App\V1\CMS\Validators
 */
class InfoUpsertValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'exists:info,id,deleted_at,NULL',
            'name' => 'required|max:200',
            'type' => 'in:LAND,SECTOR',
            'description' => 'max:300',
        ];
    }

    protected function attributes()
    {
        return [

        ];
    }
}