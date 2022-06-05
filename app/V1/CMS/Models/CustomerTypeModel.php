<?php
/**
 * User: Administrator
 * Date: 01/01/2019
 * Time: 09:54 PM
 */

namespace App\V1\CMS\Models;


use App\CustomerType;
use App\SSC;
use App\Supports\Message;

class CustomerTypeModel extends AbstractModel
{
    public function __construct(CustomerType $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $customerType = CustomerType::find($id);
            if (empty($customerType)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $customerType->name = array_get($input, 'name', $customerType->name);
            $customerType->code = array_get($input, 'code', $customerType->code);
            $customerType->point = array_get($input, 'point', $customerType->point);
            $customerType->description = array_get($input, 'description', NULL);
            $customerType->icon = array_get($input, 'icon', NULL);
            $customerType->updated_at = date("Y-m-d H:i:s", time());
            $customerType->updated_by = SSC::getCurrentUserId();
            $customerType->save();
        } else {
            $param = [
                'code' => $input['code'],
                'name' => $input['name'],
                'point' => $input['point'],
                'description' => array_get($input, 'description'),
                'icon' => array_get($input, 'icon'),
                'is_active' => 1,
            ];
            $customerType = $this->create($param);
        }

        return $customerType;
    }
}
