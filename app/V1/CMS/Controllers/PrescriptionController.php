<?php
/**
 * User: Administrator
 * Date: 12/10/2018
 * Time: 06:27 PM
 */

namespace App\V1\CMS\Controllers;


use App\Diseases;
use App\DiseasesDiagnosed;
use App\HealthBookRecord;
use App\MedicalHistory;
use App\Medicine;
use App\OFFICE;
use App\Prescription;
use App\PrescriptionDetail;
use App\Profile;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\TestResult;
use App\V1\CMS\Models\PrescriptionDetailModel;
use App\V1\CMS\Models\PrescriptionModel;
use App\V1\CMS\Traits\ControllerTrait;
use App\V1\CMS\Transformers\Permission\PermissionTransformer;
use App\V1\CMS\Transformers\Prescription\PrescriptionDrTransformer;
use App\V1\CMS\Transformers\Prescription\PrescriptionListOfDrTransformer;
use App\V1\CMS\Transformers\Prescription\PrescriptionListTransformer;
use App\V1\CMS\Transformers\Prescription\PrescriptionTransformer;

use App\V1\CMS\Validators\PrescriptionCreateValidator;
use App\V1\CMS\Validators\PrescriptionUpdateValidator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class PrescriptionController extends BaseController
{
    use ControllerTrait;

    /**
     * @var PrescriptionModel
     */
    protected $model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new PrescriptionModel();
    }

    /**
     * @param Request $request
     * @param PermissionTransformer $permissionTransformer
     * @return \Dingo\Api\Http\Response
     */


    public function search(Request $request, PrescriptionTransformer $transformer)
    {
        $input = $request->all();
        try {
            $roles = $this->model->searchPres($input, [], array_get($input, 'limit', 20));
            Log::view($this->model->getTable());
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($roles, $transformer);
    }

    public function searchDrCreated(Request $request, PrescriptionDrTransformer $transformer)
    {
        $input = $request->all();

        $userId = OFFICE::getCurrentUserId();

        try {
            $input['created_by'] = $userId;
            $roles = $this->model->searchPres($input, [], array_get($input, 'limit', 2000));
            Log::view($this->model->getTable());
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($roles, $transformer);
    }

    //Lay du lieu toa thuoc chỉ riêng đối vs bác sĩ cho bệnh nhân đó
    public function searchDoctorsIdPatient($id, Request $request, PrescriptionListTransformer $transformer)
    {
        $input = $request->all();
        $userId = OFFICE::getCurrentUserId();
        try {
            $input['created_by'] = $userId;
            $input['user_patients'] = $id;
            $roles = $this->model->searchPres($input, [], array_get($input, 'limit', 20));
            Log::view($this->model->getTable());
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($roles, $transformer);
    }

    //Toan bo dữ liệu toa thuôc của bênh nhân
    public function searchDoctors($id, Request $request, PrescriptionListTransformer $transformer)
    {
        $input = $request->all();
        $userId = OFFICE::getCurrentUserId();
        try {
            $input['user_patients'] = $id;
            $roles = $this->model->searchPres($input, [], array_get($input, 'limit', 20));
            Log::view($this->model->getTable());
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($roles, $transformer);
    }

    //Toa thuôc của bênh nhân
    public function searchPatients(Request $request, PrescriptionListTransformer $transformer)
    {
        $input = $request->all();
        $userId = OFFICE::getCurrentUserId();
        try {
            $input['user_patients'] = $userId;
            $roles = $this->model->searchPres($input, [], array_get($input, 'limit', 20));
            Log::view($this->model->getTable());
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($roles, $transformer);
    }

    //Toa thuôc của bệnh nhân
    public function searchDoctorPre(Request $request, PrescriptionListOfDrTransformer $transformer)
    {
        $input = $request->all();
        $userId = OFFICE::getCurrentUserId();
        try {
            $input['user_doctors'] = $userId;
            $roles = $this->model->searchPres($input, [], array_get($input, 'limit', 100));
            Log::view($this->model->getTable());
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($roles, $transformer);
    }

    public function detail($id, PrescriptionTransformer $transformer)
    {
        try {
            $pre = $this->model->getFirstBy('id', $id);
            Log::view($this->model->getTable());
            if (empty($pre)) {
                return ["data" => Response::HTTP_NO_CONTENT];
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

    public function create(
        Request $request,
        PrescriptionCreateValidator $createValidator,
        PrescriptionTransformer $transformer
    )
    {
        $input = $request->all();
        $createValidator->validate($input);
        try {
            DB::beginTransaction();
            $preModel = $this->model->upsert($input);
            Log::create($this->model->getTable(), $preModel->code);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ["id" => $preModel->id, 'message' => Message::get("R001", $preModel->id), 'status' => Message::get("R001", $preModel->code)];
        //return $this->response->item($preModel, $transformer);
    }

    public function addMedicines($id, Request $request, PrescriptionUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try {
            $preDetailModel = new PrescriptionDetailModel();
            $allMedicine = $preDetailModel->search(['prescription_id' => $id])->toArray();
            $allMedicine = array_pluck($allMedicine, 'prescription_id', 'medicine_id');
            $i = 0;
            // 1. Add new Medicine
            DB::beginTransaction();
            foreach ($input['medicine_id'] as $medicine_id) {
                if (empty($allMedicine[$medicine_id])) {
                    // Add prescription detail
                    //$validator->validate($medicine_id);
                    $preDetailModel->refreshModel();
                    $role = $preDetailModel->create(['prescription_id' => $id, 'medicine_id' => $medicine_id, 'use' => $input['use'][$i],
                        'unit' => $input['unit'][$i], 'numbers' => $input['numbers'][$i], 'is_active' => 1, 'deleted' => 0]);
                    // Write Log
                    $listPreDetail = $role->toArray();
                    $prescription_id = $this->GetMedicine($listPreDetail['medicine_id']);
                    Log::update($preDetailModel->getTable(), $prescription_id);
                } else {
                    unset($allMedicine[$medicine_id]);
                }
                $i++;
            }

            //2 . Delete Prescident
            foreach ($allMedicine as $medicine_id => $prescription_id) {
                $preDetailModel->refreshModel();
                $preDetailModel->deleteBy(['prescription_id', 'medicine_id'], [
                    'prescription_id' => $prescription_id,
                    'medicine_id' => $medicine_id,
                    'deleted' => 1,
                    'deleted_by' => OFFICE::getCurrentUserId(),
                ]);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => 'Successful', 'message' => "Add Prescription is Successful!"];
    }

    public function GetMedicine($id)
    {
        $getMedicine = Medicine::where(['id' => $id])->first();
        return $getMedicine;
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $permission = Prescription::find($id);
            if (empty($permission)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            $this->checkForeignTable($id, config("constants.FT.{$this->model->getTable()}", []));
            // 1. Delete PerMission
            $permission->delete();
            Log::delete($this->model->getTable(), $permission->action);
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $permission->id)];
    }

    public function checkForeignTable($id, $tables)
    {
        if (empty($tables)) {
            return true;
        }
        $result = "";
        foreach ($tables as $table_key => $table) {
            $temp = explode(".", $table_key);
            $table_name = $temp[0];
            $foreign_key = !empty($temp[1]) ? $temp[1] : 'id';
            $data = DB::table($table_name)->where($foreign_key, $id)->first();
            if (!empty($data)) {
                $result .= "$table; ";
            }
        }
        $result = trim($result, ";");
        if (!empty($result)) {
            return $this->response->errorBadRequest(Message::get("R004", $result));
        }
        return true;
    }

    public function exportPrePDFforPatients($id, Request $request)
    {
        $input = $request->all();
        try {
            $user_patients = MedicalHistory::model()->where(['prescription_id' => $id])
                ->WhereNull('medical_histories.deleted_at')->first();
            $pre_code = Prescription::model()->where(['id' => $user_patients->prescription_id])->first();
            $diseases_diagnosed = DiseasesDiagnosed::model()
                ->where(['medical_history_id' => $user_patients->id])
                ->join('diseases', 'diseases.id', '=', 'diseases_diagnosed.diseases_id')
                ->get()->toArray();

            $diseases_diagnosed = array_map(function ($diseases_diagnosed) {
                return [
                    'diseases_code' => $diseases_diagnosed['code'],
                    'diseases_name' => $diseases_diagnosed['name']
                ];
            }, $diseases_diagnosed);

            $TestResult = TestResult::model()->where(['medical_history_id' => $user_patients->id])
                ->join('analysis', 'analysis.id', '=', 'test_result.analysis_id')
                ->select([
                    'test_result.name as result',
                    'test_result.image as img_result',
                    'analysis.name as name_analysis'
                ])
                ->get()->toArray();
            $Test_Result = array_map(function ($TestResult) {
                $img_result = !empty($TestResult['img_result']) ? url('/v2') . "/img/uploads," . str_replace('/', ',', $TestResult['img_result']) : null;
                return [
                    'name_analysis' => $TestResult['name_analysis'],
                    'result' => $TestResult['name_analysis'],
                    'img_result' => $img_result,
                ];
            }, $TestResult);

            $health_record = HealthBookRecord::model()->where(['id' => $user_patients->health_record_book_id])->WhereNull('deleted_at')->first();
            $profilePatients = Profile::model()->where(['user_id' => $health_record->user_id])->WhereNull('deleted_at')->first();
            $profileDoctor = Profile::model()->where(['user_id' => $user_patients->user_id])->WhereNull('deleted_at')->first();
            $preDetailDetail = PrescriptionDetail::where([
                'prescription_id' => $user_patients->prescription_id
            ])->join('medicines', 'medicines.id', '=', 'prescriptions_details.medicine_id')->get()->toArray();

            $preDetailDetail = array_map(function ($pre) {
                return [
                    'medicine_id' => $pre['medicine_id'],
                    'medicine_code' => $pre['code'],
                    'medicine_name' => $pre['name'],
                    'medicine_unit' => $pre['unit'],
                    'medicine_numbers' => $pre['numbers'],
                    'medicine_use' => $pre['use']
                ];
            }, $preDetailDetail);

            $data = [
                'precident_code' => $pre_code->code,
                'precident_health_id' => $user_patients->health_record_book_id,
                'follow_up' => empty($user_patients->follow_up) ? null : ", Ngày tái khám " . date('d-m-Y', strtotime($user_patients->follow_up)),
                'patient_name' => $profilePatients->full_name,
                'patient_age' => (date('Y', time()) - date('Y', strtotime($profilePatients->birthday))),
                'patient_genre' => config('constants.STATUS.GENRE')
                [strtoupper(object_get($profilePatients->genre, $profilePatients->genre, 'O'))],
                'patient_address' => $profilePatients->address,
                'patient_phone' => $profilePatients->phone,
                'symptom' => $user_patients->symptom,
                'doctor_name' => $profileDoctor->full_name,
                'date' => date('d-m-Y', time()),
                'diseases' => $diseases_diagnosed,
                'test_results'=>$Test_Result ,
                'medicines' => $preDetailDetail
            ];
            //print_r($data); die;
            /*
            //method when download PDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4'
            ]);
            $html = (string)view('pdf.generate_pdf', $data);
            $mpdf->WriteHTML($html);
            $fileName = time() . $user_patients->code.".pdf";
            $y = date('Y', time());
            $m = date("m", time());
            $d = date("d", time());
            $path = public_path('prescriptionsPDF');
            if (!file_exists("$path/$profilePatients->full_name/$y/$m/$d")) {
                mkdir("$path/$profilePatients->full_name/$y/$m/$d", 0777, true);
            }
            $filePath = "$path/$profilePatients->full_name/$y/$m/$d/$fileName";
            file_put_contents($filePath, $fileName);
            $mpdf->Output($filePath,'F');
            header('Access-Control-Allow-Origin: *');
            header("Content-Type:application/pdf");
            header("Content-Disposition:attachment; filename='$fileName'");
            readfile($filePath);
            */
            $dateFile = date('d-m-Y', time());
            $nameFile = $profilePatients->full_name;
            $covertFileNameToEN = $this->convert_vi_to_en($nameFile);
            //
            $filenameFormat = str_replace(' ', '-', $covertFileNameToEN);
            $filename = "{$filenameFormat}_{$pre_code->code}_{$dateFile}";
            //print_r($filename); die;
            $this->downloadConvertPDF($data, $filename, 'prescriptionsPDF', $profilePatients->full_name);
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return true;
    }

    public function downloadConvertPDF($data, $file_name = null, $file_path = null, $user_profile = null)
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4'
        ]);
        $html = (string)view('pdf.generate_pdf', $data);
        $mpdf->WriteHTML($html);
        $fileName = $file_name . ".pdf";
        $y = date('Y', time());
        $m = date("m", time());
        $d = date("d", time());
        $path = public_path($file_path);
        if (!file_exists("$path/$user_profile/$y/$m/$d")) {
            mkdir("$path/$user_profile/$y/$m/$d", 0777, true);
        }
        $filePath = "$path/$user_profile/$y/$m/$d/$fileName";
        file_put_contents($filePath, $fileName);
        $mpdf->Output($filePath, 'F');
        header('Access-Control-Allow-Origin: *');
        header("Content-Type:application/pdf");
        header("Content-Disposition:attachment; filename={$fileName}");
        readfile($filePath);
        \Illuminate\Support\Facades\File::delete($filePath);
    }

    //Covert VietNamese to EngLish
    public function convert_vi_to_en($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
        $str = preg_replace("/(Đ)/", "D", $str);
        //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
        return $str;
    }

}