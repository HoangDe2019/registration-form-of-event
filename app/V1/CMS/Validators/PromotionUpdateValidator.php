<?php
/**
 * User: Administrator
 * Date: 22/12/2018
 * Time: 03:59 PM
 */

namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Promotion;
use App\Supports\Message;
use Illuminate\Http\Request;

class PromotionUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'title' => 'nullable|max:200',
            'code' => [
                'nullable',
                'max:20',
                function ($attribute, $value, $fail) {
                    $input = Request::capture();
                    $item = Promotion::model()->where('code', $value)->get()->toArray();
                    if (!empty($item) && count($item) > 0) {
                        if (count($item) > 1 || ($input['id'] > 0 && $item[0]['id'] != $input['id'])) {
                            return $fail(Message::get("unique", "$attribute: #$value"));
                        }
                    }
                }
            ],
            'from' => 'nullable|date_format:Y-m-d H:i:s',
            'to' => 'nullable|date_format:Y-m-d H:i:s',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id,deleted_at,NULL',
            'details.*.qty' => 'required',
            'details.*.unit' => 'required|max:50',
            'details.*.point' => 'required|integer',
            'details.*.price' => 'nullable|numeric',
            'details.*.sale_off' => 'nullable|numeric',
        ];
    }

    protected function attributes()
    {
        return [
            'title' => Message::get("title"),
            'code' => Message::get("code"),
            'from' => Message::get("from"),
            'to' => Message::get("to"),
            'details' => Message::get("detail"),
            'details.*.qty' => Message::get("quantity"),
            'details.*.product_id' => Message::get("product_id"),
            'details.*.unit' => Message::get("unit"),
            'details.*.point' => Message::get("point"),
            'details.*.price' => Message::get("price"),
            'details.*.sale_off' => Message::get("sale_off"),
        ];
    }
}
