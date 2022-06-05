<?php
/**
 * User: Administrator
 * Date: 17/10/2018
 * Time: 12:17 AM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class NotifyUpsertValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'exists:notifies,id,deleted_at,NULL',
            'title' => 'required|max:100',
            'issue_id' => 'nullable|exists:issues,id,deleted_at,NULL',
            'discuss_id' => 'nullable|exists:discuss,id,deleted_at,NULL',
            'sender' => 'required|exists:users,id,deleted_at,NULL',
            'receiver' => 'required|exists:users,id,deleted_at,NULL',
        ];
    }

    protected function attributes()
    {
        return [
            'title' => Message::get("title"),
            'issue_id' => Message::get("issue_id"),
            'discuss_id' => Message::get("discuss_id"),
            'sender' => Message::get("sender"),
            'receiver' => Message::get("receiver"),
        ];
    }
}