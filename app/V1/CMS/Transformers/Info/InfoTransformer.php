<?php
/**
 * User: Ho Sy Dai
 * Date: 10/3/2018
 * Time: 1:52 PM
 */

namespace App\V1\CMS\Transformers\Info;


use App\Info;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class InfoTransformer extends TransformerAbstract
{
    public function transform(Info $info)
    {
        try {
            return [
                'id' => $info->id,
                'name' => $info->name,
                'type' => $info->type,
                'description' => $info->description,
                'is_active' => $info->is_active,
                'updated_at' => !empty($info->updated_at) ? date('d/m/Y H:i', strtotime($info->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
