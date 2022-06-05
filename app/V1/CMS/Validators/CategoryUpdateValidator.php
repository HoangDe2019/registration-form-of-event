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
use Illuminate\Http\Request;

class CategoryUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'required|exists:categories,id,deleted_at,NULL',
            'code' => [
                'nullable',
                'max:20',
                function ($attribute, $value, $fail) {
                    $input = Request::capture();
                    $item = Category::model()->where('code', $value)->get()->toArray();
                    if (!empty($item) && count($item) > 0) {
                        if (count($item) > 1 || ($input['id'] > 0 && $item[0]['id'] != $input['id'])) {
                            return $fail(Message::get("unique", "$attribute: #$value"));
                        }
                    }
                }
            ],
            'name' => 'nullable|max:50',
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