<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 11:38 AM
 */

namespace App\V1\CMS\Validators;

use App\ModuleCategory;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleCategoryCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'module_id' => 'required|exists:modules,id,deleted_at,NULL',
            'code' => [
                'required',
                'max:20',
                function ($attribute, $value, $fail) {
                    $input = Request::capture();
                    if (!empty($value)) {
                        $task = ModuleCategory::Model()
                            ->where('code', $value)
                            ->where('module_id', $input['module_id'])
                            ->first();
                        if (!empty($task)) {
                            return $fail(Message::get("unique", "$attribute: #$value"));
                        }
                    }
                    return true;
                }
            ],
            'name' => 'required|max:50',
        ];
    }

    protected function attributes()
    {
        return [
            'code' => Message::get("code"),
            'name' => Message::get("name"),
            'module_id' => Message::get("module_id"),
        ];
    }
}