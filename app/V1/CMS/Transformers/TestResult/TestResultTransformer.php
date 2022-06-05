<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\TestResult;

use App\TestResult;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class TestResultTransformer extends TransformerAbstract
{
    public function transform(TestResult $result)
    {
        try {
            $imgData = !empty($result->image) ? url('/v2') . "/img/uploadsTestResult," . str_replace('/',',',$result->image) : null;

            return [
                'medical_history_id' => $result->medical_history_id,
                'analysis_id' => $result->analysis_id,
                'name' => $result->name,
                'image' =>$imgData,
                'created_at' => date('d/m/Y H:i', strtotime($result->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($result->updated_at)),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
