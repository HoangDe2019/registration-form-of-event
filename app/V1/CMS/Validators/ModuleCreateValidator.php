<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/22/2019
 * Time: 1:44 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Module;
use App\Supports\Message;

class ModuleCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'code' => [
                'required',
                'max:20',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $task = Module::Model()->where('code', $value)->first();
                        if (!empty($task)) {
                            return $fail(Message::get("unique", "$attribute: #$value"));
                        }
                    }
                    return true;
                }
            ],
            'name' => 'required|max:50',
            'user_module_details' => 'required|array',
            'user_module_details.*.id' => 'required',
            'user_module_details.*.name' => 'required',
        ];
    }

    protected function attributes()
    {
        return [
            'code' => Message::get("code"),
            'name' => Message::get("name"),
            'user_module_details' => Message::get("user_module_details"),
            'user_module_details.*.id' => Message::get("user_module_details"),
            'user_module_details.*.name' => Message::get("user_module_details")
        ];
    }
}