<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\Week;

use App\Week;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class WeekTransformer extends TransformerAbstract
{
    public function transform(Week $week)
    {
        try {
            return [
                'id' => $week->id,
                'code' => $week->code,
                'time_begin' => date('d/m/Y', strtotime($week->checkin)),
                'time_end' => date('d/m/Y', strtotime($week->checkout)),
                'state' => $week->state,
                'description' => $week->description,
                'created_at' => date('d/m/Y H:i', strtotime($week->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($week->updated_at)),
//                'deleted_at' => date('d/m/Y H:i', strtotime($week->deleted_at))
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
