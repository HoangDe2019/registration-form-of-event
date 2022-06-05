<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/22/2019
 * Time: 1:53 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Module;
use App\Supports\Message;
use Illuminate\Http\Request;

class ModuleUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'required|exists:modules,id,deleted_at,NULL',
            'code' => [
                'nullable',
                'max:10',
                function ($attribute, $value, $fail) {
                    $input = Request::capture();
                    $item = Module::where('code', $value)->whereNull('deleted_at')->get()->toArray();
                    if (!empty($item) && count($item) > 0) {
                        if (count($item) > 1 || ($input['id'] > 0 && $item[0]['id'] != $input['id'])) {
                            return $fail(Message::get("unique", "$attribute: #$value"));
                        }
                    }
                }
            ],
            'name' => 'nullable|max:50',
            'user_module_details' => 'required|array',
            'user_module_details.*.id' => 'required',
            'user_module_details.*.name' => 'required',
        ];
    }

    protected function attributes()
    {
        return [
            'id' => Message::get("id"),
            'code' => Message::get("code"),
            'name' => Message::get("name"),
            'user_module_details' => Message::get("user_module_details"),
            'user_module_details.*.id' => Message::get("user_module_details"),
            'user_module_details.*.name' => Message::get("user_module_details")
        ];
    }
}