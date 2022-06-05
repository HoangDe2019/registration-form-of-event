<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Controllers;

use App\Company;
use App\OFFICE;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\CompanyModel;
use App\V1\CMS\Transformers\Company\CompanyTransformer;
use App\V1\CMS\Validators\CompanyValidator\CompanyCreateValidator;
use App\V1\CMS\Validators\CompanyValidator\CompanyUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends BaseController
{

    /**
     * CompanyController constructor.
     */
    public function __construct()
    {
        $this->model = new CompanyModel();
    }

    /**
     * @param Request $request
     * @param CompanyTransformer $CompanyTransformer
     * @return \Dingo\Api\Http\Response
     */

    public function search(Request $request, CompanyTransformer $companyTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        if (!OFFICE::isSuper()) {
            $input['id'] = OFFICE::getCurrentCompanyId();
        }
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable());
        return $this->response->paginator($result, $companyTransformer);
    }

    /**
     * @param $id
     * @param CompanyTransformer $companyTransformer
     * @return \Dingo\Api\Http\Response|null[]
     */
    public function detail($id, CompanyTransformer $companyTransformer)
    {
        $result = Company::find($id);
        if (empty($result)) {
            return ["data" => null];
        }
        Log::view($this->model->getTable());
        return $this->response->item($result, $companyTransformer);
    }

    /**
     * @param Request $request
     * @param CompanyCreateValidator $companyCreateValidator
     * @return array|void
     */
    public function create(Request $request, CompanyCreateValidator $companyCreateValidator)
    {
        $input = $request->all();
        $companyCreateValidator->validate($input);
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

    /**
     * @param $id
     * @param Request $request
     * @param CompanyUpdateValidator $companyUpdateValidator
     * @return array|void
     */
    public function update($id, Request $request, CompanyUpdateValidator $companyUpdateValidator)
    {
        $input = $request->all();
        $input['id'] = $id;
        $companyUpdateValidator->validate($input);
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

    /**
     * @param $id
     * @return array|void
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = Company::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // 1. Delete Company
            $result->delete();
            Log::delete($this->model->getTable(), $result->name);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->code)];
    }
}
