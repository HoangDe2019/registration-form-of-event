<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\BookBefore;

use App\BookBefore;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class BookBeforeTransformer extends TransformerAbstract
{
    public function transform(BookBefore $before)
    {
        try {
            $avatar_doctors = !empty($before->schedule->user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$before->schedule->user->profile->avatar) : null;
            $avatar_patients = !empty($before->user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$before->user->profile->avatar) : null;

            return [
                'id' => $before->id,
                'health_record_pt_id'=>object_get($before, 'User.health_record_books.id',null),
                'patient_id' => $before->user_id,
                'patient_name' => object_get($before, 'User.profile.full_name', null),
                'avatar_patients'=>$avatar_patients,
                'patient_address' => object_get($before, 'User.profile.address', null),
                'patient_phone' => object_get($before, 'User.phone', null),
                'who_book' => $before->name,
                'work_date' => date('d-m-Y',strtotime(object_get($before, 'schedule.checkin', null))),
                'session' => object_get($before, 'schedule.session', null),
                'status' =>$before->state,
                'create_book'=>date('d-m-Y H:i:s', strtotime($before->created_at)),
                'book_time' => $before->time,
                'doctor_id' => object_get($before, 'schedule.User.id', null),
                'doctor_name' => object_get($before, 'schedule.User.profile.full_name', null),
                'avatar_doctors'=>$avatar_doctors,
                'specialist' =>object_get($before, 'schedule.User.department.name', null),

            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
