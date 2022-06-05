<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\TimeOfDay;

use App\TimeOfDay;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class TimeOfDayTransformer extends TransformerAbstract
{
    public function transform(TimeOfDay $timeOfDay)
    {
        try {
            return [
                'id' => $timeOfDay->id,
                'from' => date('H:i', strtotime($timeOfDay->start_time)),
                'to' => date('H:i', strtotime($timeOfDay->end_time)),
                'session' => config('constants.SESSIONTIMEOUT.SESSION')
                [strtoupper(object_get($timeOfDay, "session", 'O'))],
                'books_before'=>array_get($timeOfDay,'bookBefore_timeofday', null),
                'created_at' => date('Y-m-d H:i', strtotime($timeOfDay->created_at)),
                'updated_at' => date('Y-m-d H:i', strtotime($timeOfDay->updated_at)),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
