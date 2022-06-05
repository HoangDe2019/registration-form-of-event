<?php

namespace App\V1\CMS\Controllers;

use App\Analysis;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Controllers\BaseController;
use App\V1\CMS\Models\AnalysisModel;
use App\V1\CMS\Transformers\Analysis\AnalysisTransformer;
use App\V1\CMS\Validators\AnalysisCreateValidator;
use App\V1\CMS\Validators\AnalysisUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new AnalysisModel();
    }

    public function search(Request $request, AnalysisTransformer $transformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function detailAnalysis($id, AnalysisTransformer $transformer)
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

    public function create(Request $request, AnalysisCreateValidator $createValidator, AnalysisTransformer $transformer)
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
//        return $this->response->item($result, $transformer);
    }

    public function update($id, Request $request, AnalysisUpdateValidator $updateValidator, AnalysisTransformer $transformer)
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
        return ['status' => Message::get("R001", $result->code)];
        //return $this->response->item($result, $transformer);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = Analysis::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            //Department::model()->where('company_id', $id)->delete();
            $result->delete();
            Log::delete($this->model->getTable(), $result->id);
            DB::commit();

        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->id)];

       // return ['status' => Message::get("delete-success", $result->id)];
    }


}