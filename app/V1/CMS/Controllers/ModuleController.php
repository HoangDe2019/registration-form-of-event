<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/22/2019
 * Time: 11:23 AM
 */

namespace App\V1\CMS\Controllers;


use App\Module;
use App\ModuleHasUser;
use App\OFFICE;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\ModuleModel;
use App\V1\CMS\Transformers\Module\ModuleHasUserTransformer;
use App\V1\CMS\Transformers\Module\ModuleTransformer;
use App\V1\CMS\Validators\ModuleCreateValidator;
use App\V1\CMS\Validators\ModuleUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleController extends BaseController
{
    /**
     * @var ModuleModel
     */
    protected $model;

    /**
     * ModuleCategoryController constructor.
     */

    public function __construct()
    {
        $this->model = new ModuleModel();
    }

    public function search(Request $request, ModuleTransformer $moduleTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable());
        return $this->response->paginator($result, $moduleTransformer);
    }

    public function detail($id, ModuleTransformer $moduleTransformer)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            Log::view($this->model->getTable());
            if (empty($result)) {
                return ["data" => []];
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }

        return $this->response->item($result, $moduleTransformer);
    }

    public function userDetail($id, Request $request, ModuleHasUserTransformer $moduleHasUserTransformer)
    {
        $input = $request->all();
        try {
            $moduleHasUserModel = new ModuleHasUser();
            $result = $moduleHasUserModel
                ->where('module_id', $id);

            $result = $result->whereHas('users.profile', function ($q) use ($input) {
                if (!empty($input['user_name'])) {
                    $q->where('full_name', 'like', "%{$input['user_name']}%");
                }
            });
            $results = $result->get();
            $param = [];
            foreach ($results as $result) {
                $moduleHasUser = ModuleHasUser::where('user_id', $result['user_id'])->first();
                $param[] = [
                    'id' => $moduleHasUser->user_id,
                    'name' => object_get($moduleHasUser, 'users.profile.full_name', null)
                ];
            }
            Log::view($this->model->getTable());
            if (empty($result)) {
                return ["data" => []];
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return ['data' => $param];
    }

    public function create(Request $request, ModuleCreateValidator $moduleCreateValidator, ModuleTransformer $moduleTransformer)
    {
        $input = $request->all();
        $moduleCreateValidator->validate($input);

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
        return $this->response->item($result, $moduleTransformer);
    }

    public function update(
        $id,
        Request $request,
        ModuleUpdateValidator $moduleUpdateValidator,
        ModuleTransformer $moduleTransformer
    )
    {
        $input = $request->all();
        $input['id'] = $id;
        $moduleUpdateValidator->validate($input);
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
        return $this->response->item($result, $moduleTransformer);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = Module::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // 1. Delete Module
            $result->delete();
            Log::delete($this->model->getTable(), $result->name);
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("category_task.delete-success", $result->code)];
    }
}