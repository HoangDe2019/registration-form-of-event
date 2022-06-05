<?php

namespace App\V1\CMS\Controllers;
use App\Medicine;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\MedicineModel;
use App\V1\CMS\Transformers\Medicine\MedicineTransformer;
use App\V1\CMS\Validators\MedicineCreateValidator;
use App\V1\CMS\Validators\MedicineUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MedicineController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new MedicineModel();
    }

    public function search(Request $request, MedicineTransformer $transformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable());
        return $this->response->paginator($result, $transformer);
    }

    public function detail($id, MedicineTransformer $transformer)
    {
        $result = $this->model->getFirstBy('id', $id);
        if (empty($result)) {
            return ["data" => 'Không tìm thấy'];
        }
        Log::view($this->model->getTable());
        return $this->response->item($result, $transformer);
    }

    public function create(Request $request, MedicineCreateValidator $createValidator, MedicineTransformer $transformer)
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

    public function update($id, Request $request, MedicineUpdateValidator $updateValidator, MedicineTransformer $transformer)
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
        return ['status' => Message::get("R002", $result->code)];
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = Medicine::find($id);
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