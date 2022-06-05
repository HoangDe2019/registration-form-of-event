<?php

namespace App\V1\CMS\Controllers;

use App\Diseases;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Controllers\BaseController;
use App\V1\CMS\Models\DiseasesModel;
use App\V1\CMS\Transformers\Diseases\DiseasesTransformer;
use App\V1\CMS\Validators\DiseasesCreateValidator;
use App\V1\CMS\Validators\DiseasesUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiseasesController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new DiseasesModel();
    }

    public function search(Request $request, DiseasesTransformer $transformer)
    {
        $input  = $request->all();
        $limit  = array_get($input, 'limit', 20);
        $result = $this->model->searchDieases($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function detail($id, DiseasesTransformer $transformer)
    {
        $result = $this->model->getFirstBy('id', $id);
        if (empty($result)) {
            return ["data" => 'Không tìm thấy'];
        }
        return $this->response->item($result, $transformer);
    }

    public function create(Request $request, DiseasesCreateValidator $createValidator, DiseasesTransformer $transformer)
    {
        $input = $request->all();
        $createValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::create($this->model->getTable(), $result->code);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R001", $result->code)];
    }

    public function update($id, Request $request, DiseasesUpdateValidator $updateValidator, DiseasesTransformer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $updateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable(), $result->code);
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
            $result = Diseases::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            $result->delete();
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->name)];
    }

}