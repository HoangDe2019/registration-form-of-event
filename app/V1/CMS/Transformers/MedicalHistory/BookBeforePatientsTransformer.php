<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\MedicalHistory;

use App\BookBefore;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class BookBeforePatientsTransformer extends TransformerAbstract
{
    public function transform(BookBefore $before)
    {
        try {
            return [
                'id' => $before->id,
                'user_id' => $before->user_id,
                'user_name_patient' => object_get($before, 'User.profile.full_name', null),
                'phone' => object_get($before, 'User.phone', null),
                'full_name_book' => $before->name,
                'date_work' => object_get($before, 'schedule.checkin', null),
                'session' => object_get($before, 'schedule.session', null),
                'book_time' => $before->time,
                'user_name_doctor' => object_get($before, 'schedule.User.Profile.full_name', null),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
