<?php
/**
 * User: Administrator
 * Date: 01/01/2019
 * Time: 10:07 PM
 */

namespace App\V1\CMS\Transformers\CustomerType;


use App\CustomerType;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class CustomerTypeTransformer extends TransformerAbstract
{
    public function transform(CustomerType $customerType)
    {
        try {
            return [
                'id' => $customerType->id,
                'code' => $customerType->code,
                'name' => $customerType->name,
                'point' => $customerType->point,
                'description' => $customerType->description,
                'icon' => $customerType->icon,
                'is_active' => $customerType->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($customerType->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($customerType->updated_at)),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
