<?php

namespace App\V1\CMS\Controllers;

use App\Medicine;
use App\OFFICE;
use App\V1\CMS\Validators\MedicineOriginUpdateValidator;
use App\V1\CMS\Validators\MedicineOriginCreateValidator;
use App\MedicineOrigin;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Controllers\BaseController;
use App\V1\CMS\Models\MedicineOriginModel;
use App\V1\CMS\Transformers\MedicineOrigin\MedicineOriginTransfomer;

//use App\V1\CMS\Validators\DepartmentUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineOriginController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new MedicineOriginModel();
    }

    public function search(Request $request, MedicineOriginTransfomer $transformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 100);
        $result = $this->model->searchMedicineOrigin($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function detailMedicineOrigin($id, MedicineOriginTransfomer $transformer)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            if (empty($result)) {
                return ["data" => ['Not Data']];
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->item($result, $transformer);
    }

    public function create(Request $request, MedicineOriginCreateValidator $createValidator, MedicineOriginTransfomer $transformer)
    {
        $input = $request->all();
        $createValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::create($this->model->getTable(), $result->name);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($result, $transformer);
    }

    public function update($id, Request $request, MedicineOriginUpdateValidator $updateValidator, MedicineOriginTransfomer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $updateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable(), $result->id);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($result, $transformer);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = MedicineOrigin::model()->where('id', $id)->first();
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // 1. Delete $result
            $medicine = Medicine::model()->where('medicine_origin_id', $id)->get()->toArray();
            if (!empty($medicine)) {
                return $this->response->errorBadRequest(Message::get("V0031", "ID #$id"));
            }
            $result->deleted = 1;
            $result->updated_at = date('Y-m-d H:i:s', time());
            $result->updated_by = OFFICE::getCurrentUserId();
            $result->deleted_at = date('Y-m-d H:i:s', time());
            $result->deleted_by = OFFICE::getCurrentUserId();
            $result->save();
            Log::delete($this->model->getTable(), $result->id);
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->id)];
    }
}