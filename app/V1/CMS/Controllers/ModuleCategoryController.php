<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 11:20 AM
 */

namespace App\V1\CMS\Controllers;

use App\ModuleCategory;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\ModuleCategoryModel;
use App\V1\CMS\Transformers\ModuleCategory\ModuleCategoryTransformer;
use App\V1\CMS\Validators\ModuleCategoryCreateValidator;
use App\V1\CMS\Validators\ModuleCategoryUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleCategoryController extends BaseController
{
    /**
     * @var ModuleCategoryModel
     */
    protected $model;

    /**
     * ModuleCategoryController constructor.
     */

    public function __construct()
    {
        $this->model = new ModuleCategoryModel();
    }

    /**
     * @param Request $request
     * @param ModuleCategoryTransformer $moduleCategoryTransformer
     * @return mixed
     */

    public function search(Request $request, ModuleCategoryTransformer $moduleCategoryTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        // Log::view($this->model->getTable());
        return $this->response->paginator($result, $moduleCategoryTransformer);
    }


    public function detail($id, ModuleCategoryTransformer $moduleCategoryTransformer)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            //    Log::view($this->model->getTable());
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
        return $this->response->item($result, $moduleCategoryTransformer);
    }


    public function create(Request $request, ModuleCategoryCreateValidator $issueGroupCreateValidator, ModuleCategoryTransformer $moduleCategoryTransformer)
    {
        $input = $request->all();
        $issueGroupCreateValidator->validate($input);
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
        return $this->response->item($result, $moduleCategoryTransformer);
    }

    public function update(
        $id,
        Request $request,
        ModuleCategoryUpdateValidator $issueGroupUpdateValidator,
        ModuleCategoryTransformer $moduleCategoryTransformer
    )
    {
        $input = $request->all();
        $input['id'] = $id;
        $issueGroupUpdateValidator->validate($input);
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
        return $this->response->item($result, $moduleCategoryTransformer);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = ModuleCategory::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // 1. Delete ModuleCategory
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