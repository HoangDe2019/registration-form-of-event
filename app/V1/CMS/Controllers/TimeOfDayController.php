<?php


namespace App\V1\CMS\Controllers;


use App\BookBefore;
use App\Supports\Log;
use App\Supports\Message;
use App\TimeOfDay;
use App\V1\CMS\Models\TimeOfDayModel;
use App\V1\CMS\Transformers\TimeOfDay\TimeOfDayTransformer;
use App\V1\CMS\Validators\TimeOfDayValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Supports\OFFICE_Error;

class TimeOfDayController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TimeOfDayModel();
    }

    public function search(Request $request, TimeOfDayTransformer $transformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 100);
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable(), $result);
        return $this->response->paginator($result, $transformer);
    }

    public function detailed($id, Request $request, TimeOfDayTransformer $transformer)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            Log::view($this->model->getTable(), $result->id);
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

    public function create(Request $request, TimeOfDayValidator $createValidator, TimeOfDayTransformer $transformer)
    {
        $input = $request->all();
        $createValidator->validate($input);

        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::create($this->model->getTable(), $result->code);
            DB::commit();
            return $this->response->item($result, $transformer);
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
    }

    public function update($id, Request $request, TimeOfDayValidator $updateValidator, TimeOfDayTransformer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
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
            $result = TimeOfDay::find($id);
            Log::delete($this->model->getTable(), $result);
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
}