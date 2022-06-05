<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 11:40 AM
 */

namespace App\V1\CMS\Validators;


use App\ModuleCategory;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use Illuminate\Http\Request;

class ModuleCategoryUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'required|exists:module_category,id,deleted_at,NULL',
            'code' => [
                'required',
                'max:10',
                function ($attribute, $value, $fail) {
                    $input = Request::capture();
                    $item = ModuleCategory::where('code', $value)->whereNull('deleted_at')->get()->toArray();
                    if (!empty($item) && count($item) > 0) {
                        if (count($item) > 1 || ($input['id'] > 0 && $item[0]['id'] != $input['id'])) {
                            return $fail(Message::get("unique", "$attribute: #$value"));
                        }
                    }
                }
            ],
            'name' => 'nullable|max:50',
            'module_id' => 'nullable|exists:modules,id,deleted_at,NULL',
        ];
    }

    protected function attributes()
    {
        return [
            'id' => Message::get("id"),
            'code' => Message::get("code"),
            'name' => Message::get("name"),
            'module_id' => Message::get("module_id"),
        ];
    }
}