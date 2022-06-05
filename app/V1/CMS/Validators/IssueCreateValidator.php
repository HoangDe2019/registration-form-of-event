<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 10:24 AM
 */

namespace App\V1\CMS\Validators;

use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use App\Issue;

class IssueCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'module_category_id' => 'required|exists:module_category,id,deleted_at,NULL',
            'file_id' => 'nullable|exists:files,id,deleted_at,NULL',
            'name' => 'required|max:100',
            'deadline' => 'nullable|date_format:d-m-Y H:i',
            'start_time' => 'nullable|date_format:d-m-Y H:i',
            'priority' => 'required',
            'user_id' => 'required|array',
            'user_id.*.id' => 'required',
            'user_id.*.name' => 'required',

        ];
    }

    protected function attributes()
    {
        return [
            'name' => Message::get("name"),
            'module_category_id' => Message::get("module_category_id"),
            'deadline' => Message::get("deadline"),
            'start_time' => Message::get("deadline"),
            'progress' => Message::get("progress"),
            'user_id' => Message::get("user_id"),
            'user_id.*.id' => Message::get("user_id"),
            'user_id.*.name' => Message::get("user_id"),
            'priority' => Message::get("priority"),
        ];
    }
}