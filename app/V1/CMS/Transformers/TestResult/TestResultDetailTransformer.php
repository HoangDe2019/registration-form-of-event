<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\TestResult;

use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\TestResult;
use App\V1\CMS\Models\AnalysisModel;
use App\V1\CMS\Models\ProfileModel;
use App\V1\CMS\Models\TestResultModel;
use App\V1\CMS\Models\UserModel;
use League\Fractal\TransformerAbstract;

class TestResultDetailTransformer extends TransformerAbstract
{
    public function transform(TestResult $diagnosed)
    {
        try {
            $analysis = new AnalysisModel();
            $testResult = new TestResultModel();
            $user = new UserModel();
            $profile = new ProfileModel();

           // print_r($diagnosed->medical_history_id); die;
            $testResultResponse = TestResult::model()
                ->select([
                    $analysis->getTable() . '.name as analysis_name',
                    $testResult->getTable() . '.image as img',
                    $testResult->getTable() . '.name as test_result',
                    $profile->getTable() . '.full_name as test_doctor',
                    $testResult->getTable() . '.created_at as test_created_at',
                    $testResult->getTable() . '.updated_at as test_updated_at'
                ])
                ->where('test_result.medical_history_id', '=', $diagnosed->medical_history_id)
                ->whereNull($analysis->getTable() . '.deleted_at')
                ->join($analysis->getTable(), $analysis->getTable() . '.id', '=',
                    $testResult->getTable() . '.analysis_id')
                ->join($user->getTable(), $user->getTable() . '.id','=',$testResult->getTable() . '.user_id')
                ->join($profile->getTable(), $profile->getTable().'.user_id', '=',$user->getTable() . '.id')
                ->get()->toArray();

            if (empty($testResultResponse)){
                throw new \Exception(Message::get("V002", Message::get("204")));
            }

            $testResultResponse = array_map(function ($pre) {
                return [
                    'img'=>!empty($pre['img']) ? url('/v2') . "/img/uploadsTestResult," . $pre['img'] : null,
                    'disease_name'=>$pre['analysis_name'],
                    'test_result' => $pre['test_result'],
                    'test_doctor'=>$pre['test_doctor'],
                    'created_at'=>$pre['test_created_at'],
                    'update_at'=>$pre['test_updated_at'],
                ];
            }, $testResultResponse);
            
            $response = [
                'medical_history_id' => $diagnosed->medical_history_id,
                'test_results'=>$testResultResponse,
            ];

            return $response;
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
