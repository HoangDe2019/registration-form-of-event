<?php
/**
 * User: Administrator
 * Date: 01/01/2019
 * Time: 09:54 PM
 */

namespace App\V1\CMS\Models;


use App\CustomerGroup;
use App\SSC;
use App\Supports\Message;

class CustomerGroupModel extends AbstractModel
{
    public function __construct(CustomerGroup $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $customerGroup = CustomerGroup::find($id);
            if (empty($customerGroup)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $customerGroup->name = array_get($input, 'name', $customerGroup->name);
            $customerGroup->code = array_get($input, 'code', $customerGroup->code);
            $customerGroup->description = array_get($input, 'description', NULL);
            $customerGroup->updated_at = date("Y-m-d H:i:s", time());
            $customerGroup->updated_by = SSC::getCurrentUserId();
            $customerGroup->save();
        } else {
            $param = [
                'code' => $input['code'],
                'name' => $input['name'],
                'description' => array_get($input, 'description'),
                'is_active' => 1,
            ];
            $customerGroup = $this->create($param);
        }

        return $customerGroup;
    }
}
