<?php

namespace App\V1\CMS\Controllers;

use App\Department;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Controllers\BaseController;
use App\V1\CMS\Models\DepartmentModel;
use App\V1\CMS\Transformers\Department\DepartmentTransformer;
use App\V1\CMS\Transformers\Department\DiseasesOfDepartmentsTransformer;
use App\V1\CMS\Validators\DepartmentCreateValidator;
use App\V1\CMS\Validators\DepartmentUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new DepartmentModel();
    }

    public function search(Request $request, DepartmentTransformer $departmentTransformer)
    {
        $input  = $request->all();
        $limit  = array_get($input, 'limit', 20);
        $result = $this->model->searchWeekofUsers($input, [], $limit);
        return $this->response->paginator($result, $departmentTransformer);
    }

    public function detailDepartment($id, DiseasesOfDepartmentsTransformer $departmentTransformer)
    {
        $result = $this->model->getFirstBy('id', $id);
        if (empty($result)) {
            return ["data" => []];
        }
        return $this->response->item($result, $departmentTransformer);
    }

    public function create(Request $request, DepartmentCreateValidator $createValidator, DepartmentTransformer $transformer)
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
        return ['status' => Message::get("R001", $result->code)];
    }

    public function update($id, Request $request, DepartmentUpdateValidator $updateValidator, DepartmentTransformer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $updateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable(), $result->name);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R002", $result->code)];
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = Department::find($id);
            Log::delete($this->model->getTable(), $result->id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            $result->delete();
            DB::commit();

        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->id)];
    }

    public function listDiseasesOfDepartments(Request $request, DiseasesOfDepartmentsTransformer $transformer){
        $input  = $request->all();
        $limit  = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

}