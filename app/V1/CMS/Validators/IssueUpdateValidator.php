<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 10:31 AM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use App\Issue;
use Illuminate\Http\Request;

class IssueUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'required|exists:issues,id,deleted_at,NULL',
            'progress' => 'nullable|integer',
            'module_category_id' => 'nullable|exists:module_category,id,deleted_at,NULL',
            'file_id' => 'nullable|exists:files,id,deleted_at,NULL',
            'name' => 'nullable|max:100',
            'deadline' => 'date_format:d-m-Y H:i',
            'start_time' => 'date_format:d-m-Y H:i',
            'user_id' => 'required|array',
            'user_id.*.id' => 'required',
            'user_id.*.name' => 'required',
        ];
    }

    protected function attributes()
    {
        return [
            'id' => Message::get("id"),
            'name' => Message::get("name"),
            'deadline' => Message::get("deadline"),
            'start_time' => Message::get("start_time"),
            'module_category_id' => Message::get("module_category_id"),
            'progress' => Message::get("progress"),
            'user_id' => Message::get("user_id"),
            'user_id.*.id' => Message::get("user_id"),
            'user_id.*.name' => Message::get("user_id"),
        ];
    }
}