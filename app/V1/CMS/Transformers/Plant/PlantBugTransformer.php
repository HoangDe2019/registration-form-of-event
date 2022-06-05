<?php
/**
 * User: Ho Sy Dai
 * Date: 10/30/2018
 * Time: 3:44 PM
 */

namespace App\V1\CMS\Transformers\Plant;


use App\PlantBug;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class PlantBugTransformer extends TransformerAbstract
{
    public function transform(PlantBug $plantBug)
    {
        try {
            return [
                'id' => $plantBug->id,
                'code' => $plantBug->code,
                'name' => $plantBug->name,
                'description' => $plantBug->description,
                'suggest_fix' => $plantBug->suggest_fix,
                'is_active' => $plantBug->is_active,
                'updated_at' => !empty($plantBug->updated_at) ? date('d/m/Y H:i', strtotime($plantBug->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
