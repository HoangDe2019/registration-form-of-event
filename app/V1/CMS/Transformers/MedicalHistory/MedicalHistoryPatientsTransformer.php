<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\MedicalHistory;

use App\Analysis;
use App\Diseases;
use App\MedicalHistory;
use App\Profile;
use App\Supports\OFFICE_Error;
use App\TestResult;
use League\Fractal\TransformerAbstract;

class MedicalHistoryPatientsTransformer extends TransformerAbstract
{
    public function transform(MedicalHistory $history)
    {
        try {
            $avatar = !empty($history->user->profile->avatar) ? url('/v2') . "/img/uploads," . str_replace('/', ',', $history->user->profile->avatar) : null;
           // $diseases_diagnoseds1->toArray();
            $diseases = object_get($history, 'diseases_diagnosed', null);
            if(!empty($diseases)){
                $diseases = $diseases->toArray();
                $diseases = array_map(function ($diseases) {
                    $diseasesName = Diseases::model()->where('id', '=', $diseases['diseases_id'])->first();
                    return [
                        'diseases_diagnosed_id' =>$diseases['id'],
                        'disease_name' => $diseasesName->name ?? null,
                        'disease_code' => $diseasesName->code ?? null,
                    ];
                }, $diseases);
            }

            $test_result = object_get($history, 'test_result', null);
            if(!empty($test_result)){
                $test_result = $test_result->toArray();
                $test_result = array_map(function ($test_result) {
                    $Analysis = Analysis::model()->where('id', '=', $test_result['analysis_id'])->first();
                    $x_rays = !empty($test_result['image']) ? url('/v2') . "/img/uploadsTestResult," . str_replace('/', ',', $test_result['image']) : null;

                    return [
                        'test_result_id' =>$test_result['id'],
                        'analysis_name' => $Analysis->name ?? null,
                        'test_result_name' => $test_result['name'] ?? null,
                        'x_rays' => $x_rays
                    ];
                }, $test_result);
            }

            return [
                'id' => $history->id,
                //'health_record_book_id' => $history->health_record_book_id,
                'numbers' => $history->numbers,
                'checkinDate' => date('d/m/Y', strtotime($history->checkin)),
                'prescription_id' => object_get($history, 'prescription_id', null),
                'prescription_code' => object_get($history, 'prescription.code', null),
                'symptom' => $history->symptom,
                'test_result' => $test_result ?? null,
                'diseases_diagnoseds' => $diseases ?? null,
                'doctor_id' => object_get($history, 'User.profile.user_id', null),
                'doctor_name' => object_get($history, 'User.profile.full_name', null),
                'department' => object_get($history, 'User.department.name', null),
                'avatar_doctor' => $avatar,
                'follow_up' => date('d/m/Y', strtotime($history->follow_up)),
                'created_at' => date('d-m-Y H:i', strtotime(object_get($history, 'created_at', null))),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
