<?php
/**
 * User: Administrator
 * Date: 29/09/2018
 * Time: 12:42 AM
 */

namespace App\V1\CMS\Transformers\Plant;


use App\Plant;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class PlantTransformer extends TransformerAbstract
{
    public function transform(Plant $plant)
    {
        try {
            $info_data = object_get($plant, 'group.info', []);
            $info = [];
            foreach ($info_data as $info_datum) {
                $info[] = [
                    'info_id' => object_get($info_datum, 'info.id'),
                    'info_name' => object_get($info_datum, 'info.name'),
                ];
            }
            return [
                'id' => $plant->id,
                'code' => $plant->code,
                'name' => $plant->name,
                'description' => $plant->description,

                'group_id' => object_get($plant, 'group_id', null),
                'group_name' => object_get($plant, 'group.name', null),
                'group_info_name' => object_get($plant, 'group.info_name'),

                'info_data' => $info,

                'is_active' => $plant->is_active,
                'updated_at' => !empty($plant->updated_at) ? date('d/m/Y H:i', strtotime($plant->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
