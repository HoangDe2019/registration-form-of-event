<?php
/**
 * User: Administrator
 * Date: 12/10/2018
 * Time: 06:27 PM
 */

namespace App\V1\CMS\Controllers;


use App\Diseases;
use App\DiseasesDiagnosed;
use App\MedicalHistory;
use App\Medicine;
use App\OFFICE;
use App\Permission;

use App\Prescription;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\DiseasesDiagnosedModel;
use App\V1\CMS\Models\MedicalHistoryModel;
use App\V1\CMS\Models\PermissionModel;
use App\V1\CMS\Models\PrescriptionDetailModel;
use App\V1\CMS\Models\PrescriptionModel;
use App\V1\CMS\Traits\ControllerTrait;
use App\V1\CMS\Transformers\DiseasesDiagnosed\DiseasesDiagnosedTransformer;
use App\V1\CMS\Transformers\Permission\PermissionTransformer;
use App\V1\CMS\Transformers\Prescription\PrescriptionTransformer;
use App\V1\CMS\Validators\DiseasesDiagnosedCreateCreateValidator;
use App\V1\CMS\Validators\PermissionCreateValidator;
use App\V1\CMS\Validators\PermissionUpdateValidator;
use App\V1\CMS\Validators\PrescriptionCreateValidator;
use App\V1\CMS\Validators\PrescriptionUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dingo\Api\Http\Response;

class DiseasesDiagnosedController extends BaseController
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
        $this->model = new MedicalHistoryModel();
    }

    /**
     * @param Request $request
     * @param PermissionTransformer $permissionTransformer
     * @return \Dingo\Api\Http\Response
     */

    public function search(Request $request, DiseasesDiagnosedTransformer $transformer)
    {
        $input = $request->all();
        try {
            $roles = $this->model->search($input, [], array_get($input, 'limit', 20));
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


    public function detail($id, DiseasesDiagnosedTransformer $transformer)
    {
        try {
            $pre = $this->model->getFirstBy('id', $id);
            print_r($pre); die;
            Log::view($this->model->getTable());
            if (empty($pre)) {
                return ["data" => []];
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


    public function addDiseases($id, Request $request, DiseasesDiagnosedCreateCreateValidator $validator)
    {
        $input = $request->all();
        try {
            $medical_history = MedicalHistory::model()->where(['id' => $id])->first();
            if(empty($medical_history)){
                return $this->response->errorBadRequest(Message::get("V003", "ID medilcal history $id"));
            }
            $preDetailModel = new DiseasesDiagnosedModel();
            $allMedicine = $preDetailModel->search(['medical_history_id' => $medical_history['id']])->toArray();
            $allMedicine = array_pluck($allMedicine, 'medical_history_id', 'diseases_id');
            $i = 0;
            // 1. Add new Diseases
            DB::beginTransaction();
            foreach ($input['diseases_id'] as $diseases_id) {
                if (empty($allMedicine[$diseases_id])) {
                    // Add prescription detail
                    $preDetailModel->refreshModel();
                    //$validator->validate($diseases_id);
                    $role = $preDetailModel->create(['medical_history_id' => $id, 'diseases_id' => $diseases_id, 'state' => $input['state'][$i],
                        'description' => $input['notices'][$i], 'is_active' => 1, 'deleted' => 0]);
                    // Write Log
                    $listPreDetail = $role->toArray();
                    $namePermisson = $this->GetNamePermission($listPreDetail['diseases_id']);
                    Log::update($preDetailModel->getTable(), $namePermisson);
                } else {
                    unset($allMedicine[$diseases_id]);
                }
                $i++;
            }

            //2 . Delete Diseases
            foreach ($allMedicine as $medicine_id => $prescription_id) {
                $preDetailModel->refreshModel();
                $preDetailModel->deleteBy(['medical_history_id', 'diseases_id'], [
                    'medical_history_id' => $prescription_id,
                    'diseases_id' => $medicine_id,
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
        return ['status' => 'Successful', 'message' => "Add Diseases is Successful!"];
    }

    public function GetNamePermission($id)
    {
        $profile = Diseases::where(['id' => $id])->first();
        if(empty($profile)){
            return $this->response->errorBadRequest(Message::get("V003", "ID danh sach ma benh $id"));
        }
        return $profile;
    }
}