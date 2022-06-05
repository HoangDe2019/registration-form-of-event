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

class DiscussUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'issue_id' => 'nullable|exists:issues,id,deleted_at,NULL',
        ];
    }

    protected function attributes()
    {
        return [
            'issue_id' => Message::get("issue_id"),
            'user_id' => Message::get("user_id"),
        ];
    }
}