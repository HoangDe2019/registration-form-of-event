<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\MedicalShedule;

use App\MedicalSchedule;
use App\Profile;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class MedicalSheduleWeekUsersTransformer extends TransformerAbstract
{
    public function transform(MedicalSchedule $medicalSchedule)
    {
        try {
            $schudel = object_get($medicalSchedule, 'checkin', []);
//            die($schudel);
//            if (!empty($schudel)) {
//                $schudel = $schudel->toArray();
//                $schudel = array_map(function ($schudel) {
//                   // $profile = Profile::model()->where('user_id', $schudel['user_id'])->first();
//                    return [
//                        'id' => $schudel['id'],
//                        'dateWork' => date('d-m-Y',strtotime($schudel['checkin'])),
//                        'dateWorkEnd' => date('d-m-Y',strtotime($schudel['checkout'])),
////                        'full_name' => $profile['full_name'],
////                        'code' => $permission['code'],
//                    ];
//                }, $schudel);
//            }

            return [
                'id' => $medicalSchedule->id,
                'doctor_name' => object_get($medicalSchedule, 'User.Profile.full_name', null),
                'start_work_time' => date('d-m-Y',strtotime(object_get($medicalSchedule, 'Week.checkin', null))),
                'end_work_time' => date('d-m-Y',strtotime(object_get($medicalSchedule, 'Week.checkout', null))),
                'checkin' => date('d-m-Y', strtotime($medicalSchedule->checkin)),
                'session' => object_get($medicalSchedule, 'session', null),
                'role' => object_get($medicalSchedule, 'User.Role.name', null),
                'description' => $medicalSchedule->description,
                'test'=>$schudel
//                'created_at' => date('d/m/Y H:i', strtotime($medicalSchedule->created_at)),
//                'updated_at' => date('d/m/Y H:i', strtotime($medicalSchedule->updated_at)),
//                'deleted_at' => date('d/m/Y H:i', strtotime($medicalSchedule->deleted_at))
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
