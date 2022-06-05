<?php
/**
 * Created by PhpStorm.
 * User: kpistech2
 * Date: 2019-02-18
 * Time: 23:00
 */

namespace App\V1\CMS\Validators;


use App\Category;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;


class CategoryCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'code' => [
                'required',
                'max:20',
                function ($attribute, $value, $fail) {
                    $item = Category::model()->where('code', $value)->first();
                    if (!empty($item)) {
                        return $fail(Message::get("unique", "$attribute: #$value"));
                    }
                    return true;
                }
            ],
            'name' => 'required|max:50',
            'description' => 'max:500',
        ];
    }

    protected function attributes()
    {
        return [
            'code' => Message::get("code"),
            'name' => Message::get("alternative_name"),
            'description' => Message::get("description"),
        ];
    }
}
