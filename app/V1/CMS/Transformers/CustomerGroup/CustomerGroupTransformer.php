<?php
/**
 * User: Administrator
 * Date: 01/01/2019
 * Time: 10:07 PM
 */

namespace App\V1\CMS\Transformers\CustomerGroup;


use App\CustomerGroup;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class CustomerGroupTransformer extends TransformerAbstract
{
    public function transform(CustomerGroup $customerGroup)
    {
        try {
            return [
                'id' => $customerGroup->id,
                'code' => $customerGroup->code,
                'name' => $customerGroup->name,
                'description' => $customerGroup->description,
                'is_active' => $customerGroup->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($customerGroup->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($customerGroup->updated_at)),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
