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
use App\V1\CMS\Models\HealthBookRecordModel;
use App\V1\CMS\Models\MedicalHistoryModel;
use App\V1\CMS\Models\ProfileModel;
use App\V1\CMS\Models\UserModel;
use League\Fractal\TransformerAbstract;

class MedicalHistoryAllUserTransformer extends TransformerAbstract
{
    public function transform(MedicalHistory $history)
    {
        try {
        //    $avatar = !empty($history->user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$history->user->profile->avatar) : null;
            $avatar_patients = !empty($history->healthrecordbook->user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$history->healthrecordbook->user->profile->avatar) : null;
            $age =(date('Y', time()) - date('Y', strtotime(object_get($history,"healthrecordbook.user.profile.birthday",null))));


            return [
                'id' => $history->id,
                'health_record_book_id' => $history->health_record_book_id,
                'avatar_patient'=>$avatar_patients,
                'full_name' =>object_get($history,"healthrecordbook.user.profile.full_name", null),
                'age'=> $age,
                'address' =>object_get($history,"healthrecordbook.user.profile.address",null),
                'phone' =>object_get($history,"healthrecordbook.user.profile.phone",null),
                'blood_type'=>object_get($history,"healthrecordbook.user.profile.blood_group",null),
                'genre_patient' => config('constants.STATUS.GENRE')
                [strtoupper(object_get($history, "healthrecordbook.User.profile.genre", 'O'))],
                'user_id'=>array_get($history, 'healthrecordbook.user_id', null),
                'numbers' => $history->numbers,
                'checkin' => date('d/m/Y', strtotime($history->checkin)),
                'symptom' => $history->symptom,
                'prescription_id' => $history->prescription_id,
                'follow_up' => $history->follow_up,
                'remind' => $history->description,
                'created_at' => date('d-m-Y',strtotime(object_get($history, 'created_at', null))),
             //   'user_id' => $history->user_id,

            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
