<?php

namespace App\V1\CMS\Controllers;

use App\Analysis;
use App\MedicalHistory;
use App\V1\CMS\Models\TestResultModel;
use App\V1\CMS\Transformers\TestResult\TestResultDetailTransformer;
use App\V1\CMS\Transformers\TestResult\TestResultTransformer;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\OFFICE;
use App\V1\CMS\Validators\TestResultUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestResultController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new TestResultModel();
    }

    public function search(Request $request, TestResultTransformer $transformer)
    {
        $input = $request->all();
       // print_r($input); die;
        try {
            $result = $this->model->search($input, [], array_get($input, 'limit', 20));
            Log::view($this->model->getTable());
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($result, $transformer);
    }

    public function detail($id, TestResultDetailTransformer $transformer)
    {
        try {
            $pre = $this->model->getFirstBy('medical_history_id', $id);
            Log::view($this->model->getTable());
            if (empty($pre)) {
                return ["message" => "Không có dữ liệu!"];
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->item($pre, $transformer);
    }

    public function update($id, Request $request, TestResultUpdateValidator $validator, TestResultTransformer $transformer)
    {
        $input = $request->all();
        $validator->validate($input);
        try {
            $medical_history = MedicalHistory::model()->where(['id'=>$id])->first();
            if(empty($medical_history)){
                return $this->response->errorBadRequest(Message::get("V003", "ID medilcal history $id"));
            }

            //img
            if (!empty($input['image'])) {
                $y = date('Y', time());
                $m = date("m", time());
                $d = date("d", time());
                $path = public_path('uploadsTestResult');
                if (!file_exists("$path/$y/$m/$d")) {
                    mkdir("$path/$y/$m/$d", 0777, true);
                }
                $avatar = explode("base64,", $input['image']);
                if (!is_image($avatar[1])) {
                    throw new \Exception(Message::get("V002", "image"));
                }
                $imgData = base64_decode($avatar[1]);
                $fileName = strtoupper(uniqid()) . ".png";
                $filePath = "$path/$y/$m/$d/$fileName";
                $partSaveImgTest = str_replace("/", ',', "$y/$m/$d/$fileName");
                file_put_contents($filePath, $imgData);
            }

            //user lam xet nghiem
            $test_doctor=OFFICE::getCurrentUserId();
            $test_result = new TestResultModel();
            $analysis = $test_result->search(['medical_history_id' => $id])->toArray();
            $analysis = array_pluck($analysis, 'medical_history_id', 'analysis_id');

            // 1. Add new analysis
            DB::beginTransaction();
            foreach ($input['analysis_id'] as $analysis_id) {
                if (empty($analysis[$analysis_id]) ) {
                    // Add prescription detail
                    //$validator->validate($medicine_id);
                    $test_result->refreshModel();
                    $role = $test_result->create([
                        'medical_history_id' => $id,
                        'analysis_id' => $analysis_id,
                        'name'=>$input['name'],
                        'image'=>$partSaveImgTest ?? null,
                        'user_id'=>$test_doctor,
                        'is_active' => 1
                    ]);
                    // Write Log
                    $listPreDetail = $role->toArray();

                    $namePermisson = $this->GetAnalysis($listPreDetail['analysis_id']);
                    Log::update($test_result->getTable(), $namePermisson);
                } else {
                    unset($analysis[$analysis_id]);
                }
            }

            //2 . Delete analysis
            foreach ($analysis as $analysis_id => $medical_history_id) {
                $test_result->refreshModel();
                $test_result->deleteBy(['medical_history_id', 'analysis_id'], [
                    'medical_history_id' => $medical_history_id,
                    'analysis_id' => $analysis_id,
                    'deleted_by' => OFFICE::getCurrentUserId(),
                ]);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => 'Successful', 'message' => "Add analysis is Successful!"];
    }

    public function GetAnalysis($id)
    {
        $data = Analysis::where(['id' => $id])->first();
//        if(empty($data)){
//            return $this->response->errorBadRequest(Message::get("V003", "ID $id"));
//        }
        return $data;
    }
}

