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

class TestResultUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
//            'company_id' => 'required|exists:companies,id,deleted_at,NULL',
            'analysis_id' => '|exists:analysis,id,deleted_at,NULL',
            //'image' =>'required',
            'name'=>'required'
        ];
    }

    protected function attributes()
    {
        return [
            //'name' => Message::get("name")
        ];
    }
}