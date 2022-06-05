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
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class MedicalSheduleTransformer extends TransformerAbstract
{
    public function transform(MedicalSchedule $medicalSchedule)
    {
        try {
            return [
                'id' => $medicalSchedule->id,
                'doctor_name' => object_get($medicalSchedule, 'User.Profile.full_name', null),
                'start_work_time' => date('d-m-Y',strtotime(object_get($medicalSchedule, 'Week.checkin', null))),
                'end_work_time' => date('d-m-Y',strtotime(object_get($medicalSchedule, 'Week.checkout', null))),
                'checkin' => date('d-m-Y', strtotime($medicalSchedule->checkin)),
                'session' => object_get($medicalSchedule, 'session', null),
                'role' => object_get($medicalSchedule, 'User.Role.name', null),
                'department_name'=>object_get($medicalSchedule, 'user.department.name', null),
                'description' => $medicalSchedule->description,
                'employee_id'=>$medicalSchedule->user_id,
                'week_id'=>$medicalSchedule->week_id
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
