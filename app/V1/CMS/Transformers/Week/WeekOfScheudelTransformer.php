<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\Week;

use App\MedicalSchedule;
use App\Profile;
use App\User;
use App\Week;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class WeekOfScheudelTransformer extends TransformerAbstract
{
    public function transform(Week $week)
    {
        try {
            $schudel = object_get($week, 'medical_schedules', null);
            if (!empty($schudel)) {
                $schudel = $schudel->toArray();
                $schudel = array_map(function ($schudel) {
                    $user = User::model()->where(['id'=>$schudel['user_id']])->first();
                    return [

                        'user_id' => $schudel['user_id'],
                        'id_schudel' => $schudel['id'],
                        'date_work'=>$schudel['checkin'],
                        'session'=>$schudel['session'],
                        'users' => [
                            'id_user' =>$user['id'],
                            'full_name'=>$user->profile->full_name,
                            'role'=>$user->role->name,
                            'department' =>object_get($user,'department.name', null),
                        ],
                    ];
                }, $schudel);
            }

            return [
                'id' => $week->id,
                'code' => $week->code,
                'weeks_begin' => date('d/m/Y', strtotime($week->checkin)),
                'weeks_end' => date('d/m/Y', strtotime($week->checkout)),
                'state' => $week->state,
                'description' => $week->description,
                'schedule_doctor' => $schudel,
                'created_at' => date('d/m/Y H:i', strtotime($week->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($week->updated_at)),
                'deleted_at' => date('d/m/Y H:i', strtotime($week->deleted_at))
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
