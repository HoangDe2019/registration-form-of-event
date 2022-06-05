<?php
/**
 * User: Administrator
 * Date: 29/09/2018
 * Time: 12:46 AM
 */

namespace App\V1\CMS\Transformers\PlantGroup;


use App\PlantGroup;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

/**
 * Class PlantGroupTransformer
 * @package App\V1\CMS\Transformers\PlantGroup
 */
class PlantGroupTransformer extends TransformerAbstract
{
    public function transform(PlantGroup $plantGroup)
    {
        $info_data = object_get($plantGroup, 'info', []);
        $info = [];
        foreach ($info_data as $info_datum) {
            $info[] = [
                'info_id' => object_get($info_datum, 'info.id'),
                'info_name' => object_get($info_datum, 'info.name'),
            ];
        }
        try {
            return [
                'id' => $plantGroup->id,
                'code' => $plantGroup->code,
                'name' => $plantGroup->name,
                'description' => $plantGroup->description,
                'info_name' => $plantGroup->info_name,
                'info_data' => $info,
                'is_active' => $plantGroup->is_active,
                'updated_at' => !empty($plantGroup->updated_at) ? date('d/m/Y H:i',
                    strtotime($plantGroup->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
