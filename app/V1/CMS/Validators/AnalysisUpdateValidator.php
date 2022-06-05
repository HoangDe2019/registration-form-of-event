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

class AnalysisUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'name' => 'required|'
        ];
    }

    protected function attributes()
    {
        return [
            'name' => Message::get("name")
        ];
    }
}