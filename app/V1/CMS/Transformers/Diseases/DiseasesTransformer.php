<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\Diseases;

use App\Diseases;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class DiseasesTransformer extends TransformerAbstract
{
    public function transform(Diseases $diseases)
    {
        try {
            return [
                'id'          => $diseases->id,
                'code'        => $diseases->code,
                'name'        => $diseases->name,
                'symptom' => $diseases->symptom,
                'phase'   => $diseases->phase,
                'department_id'   => $diseases->department_id,
                'department_name'   => object_get($diseases,'department.name',null),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
