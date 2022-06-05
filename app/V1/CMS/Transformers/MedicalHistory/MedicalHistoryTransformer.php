<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\MedicalHistory;

use App\MedicalHistory;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class MedicalHistoryTransformer extends TransformerAbstract
{
    public function transform(MedicalHistory $history)
    {
        try {
            $avatar = !empty($history->user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$history->user->profile->avatar) : null;
            $avatar_patients = !empty($history->healthrecordbook->user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$history->healthrecordbook->user->profile->avatar) : null;
            $test_img = object_get($history, 'test_result.image', null);
            $test_result_img = !empty($test_img) ? url('/v2') . "/img/uploadsTestResult," .str_replace('/', ',', $test_img) : null;
            return [
                'id' => $history->id,
                'health_record_book_id' => $history->health_record_book_id,
                'numbers' => $history->numbers,
                'checkin' => date('d-m-Y H:i', strtotime($history->checkin)),
                'symptom' => $history->symptom,
                'prescription_id' => $history->prescription_id,
                'follow_up' => $history->follow_up,
                'remind' => $history->description,
                'user_id' => $history->user_id,
                'patient_name'=>object_get($history,'healthrecordbook.User.profile.full_name', null),
                'avatar_patients'=>$avatar_patients,
                'doctor_name' => object_get($history, 'User.profile.full_name', null),
                'doctor_id'=>object_get($history, 'User.profile.user_id', null),
                'avatar_doctor'=>$avatar,
                'created_at' => date('d-m-Y H:i',strtotime(object_get($history, 'created_at', null))),
                'test_img'=> $test_result_img,
                'test_result_name'=>object_get($history, 'test_result.name', null)
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
