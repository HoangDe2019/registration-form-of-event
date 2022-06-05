<?php

namespace App\V1\CMS\Validators;

use App\Analysis;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class AnalysisCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'max:50',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $name = Analysis::Model()->where('name', $value)->first();
                        if (!empty($name)) {
                            return $fail(Message::get("unique", "$attribute: '$value'"));
                        }
                    }
                    return true;
                }
            ],
        ];
    }


    protected function attributes()
    {
        return [
            'name' => Message::get("name"),
        ];
    }
}