<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\Department;

use App\Department;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class DepartmentTransformer extends TransformerAbstract
{
    public function transform(Department $departments)
    {
        try {
            return [
                'id'          => $departments->id,
                'code'        => $departments->code,
                'name'        => $departments->name,
                'description' => $departments->description,
                'users'=> object_get($departments, 'users',null)
/*                'company_id'   => $departments->company_id,
                'created_at'  => date('d/m/Y H:i', strtotime($departments->created_at)),
                'updated_at'  => date('d/m/Y H:i', strtotime($departments->updated_at)),
                'deleted_at'  => date('d/m/Y H:i', strtotime($departments->deleted_at))*/
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
