<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/21/2019
 * Time: 5:22 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class DiscussCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'issue_id' => 'required|exists:issues,id,deleted_at,NULL',
            'description' => 'required|',
        ];
    }

    protected function attributes()
    {
        return [
            'issue_id' => Message::get("issue_id"),
            'description' => Message::get("description"),
        ];
    }
}