<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 7/4/2019
 * Time: 3:12 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class FolderFileShareValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'file_id' => 'required',
        ];
    }

    protected function attributes()
    {
        return [
            'file_id' => Message::get("file_id"),
        ];
    }
}