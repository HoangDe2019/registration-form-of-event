<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\Analysis;

use App\Analysis;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class AnalysisTransformer extends TransformerAbstract
{
    public function transform(Analysis $analysis)
    {
        try {
            return [
                'id' => $analysis->id,
                'name' => $analysis->name,
                'description' => $analysis->description,
                'is_active' => $analysis->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($analysis->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($analysis->updated_at)),
                'deleted_at' => date('d/m/Y H:i', strtotime($analysis->deleted_at))
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
