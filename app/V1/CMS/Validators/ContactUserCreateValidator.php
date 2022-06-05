<?php
/**
 * User: Administrator
 * Date: 16/10/2018
 * Time: 08:44 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class ContactUserCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'exists:contacts,id,deleted_at,NULL',
            'subject_id' => 'exists:contact_info,id,deleted_at,NULL',
            'about_id' => 'exists:contact_info,id,deleted_at,NULL',
            'content' => 'required'
        ];
    }

    protected function attributes()
    {
        return [
            'subject_id' => Message::get("subject_id"),
            'about_id' => Message::get("about_id"),
            'content' => Message::get("content"),
        ];
    }
}
