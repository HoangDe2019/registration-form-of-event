<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/13/2019
 * Time: 9:32 PM
 */

namespace App\V1\CMS\Controllers;


use App\Supports\Log;
use App\V1\CMS\Models\UserLogModel;
use App\V1\CMS\Transformers\Log\LogUserTransformer;
use Illuminate\Http\Request;

class LogController extends BaseController
{
    protected $model;

    /**
     * ModuleCategoryController constructor.
     */

    public function __construct()
    {
        $this->model = new UserLogModel();
    }


    public function logUser(Request $request, LogUserTransformer $logUserTransformer)
    {
        $input = $request->all();
        try {
            $logs = $this->model->search($input, [], array_get($input, 'limit', 20));
            // Log::view($this->model->getTable());
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($logs, $logUserTransformer);
    }
}