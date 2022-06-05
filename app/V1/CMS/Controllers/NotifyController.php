<?php
/**
 * User: Administrator
 * Date: 17/10/2018
 * Time: 12:03 AM
 */

namespace App\V1\CMS\Controllers;

use App\OFFICE;
use App\Notify;
use App\NotifyUser;
use App\Supports\OFFICE_Error;
use App\Supports\Message;
use App\User;
use App\V1\CMS\Models\NotifyModel;
use App\V1\CMS\Transformers\Notify\NotifyTransformer;
use App\V1\CMS\Validators\NotifyUpsertValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifyController extends BaseController
{

    /**
     * @var NotifyModel
     */
    protected $model;

    /**
     * NotifyController constructor.
     * @param NotifyModel $model
     */
    public function __construct(NotifyModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function index()
    {
        return ['notify-status' => '0k'];
    }

    public function search(Request $request, NotifyTransformer $notifyTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        //  Log::view($this->model->getTable());
        return $this->response->paginator($result, $notifyTransformer);
    }

    /**
     * @param $id
     * @param Request $request
     * @param NotifyTransformer $notifyTransformer
     * @return \Dingo\Api\Http\Response|string
     */
    public function detail($id, Request $request, NotifyTransformer $notifyTransformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $notify = Notify::find($id);
        if (empty($notify)) {
            return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
        }
        //  Log::view($this->model->getTable());
        return $this->response->item($notify, $notifyTransformer);
    }

    /**
     * @param Request $request
     * @param NotifyUpsertValidator $notifyUpsertValidator
     * @return array|void
     */
    public function create(Request $request, NotifyUpsertValidator $notifyUpsertValidator)
    {
        $input = $request->all();
        $notifyUpsertValidator->validate($input);
        try {
            $this->model->upsert($input);
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("notifies.create-success", $input['title'])];
    }

    /**
     * @param $id
     * @param Request $request
     * @param NotifyUpsertValidator $notifyUpsertValidator
     * @return array|void
     */
    public function update($id, Request $request, NotifyUpsertValidator $notifyUpsertValidator)
    {
        $input = $request->all();
        $input['id'] = $id;
        $notifyUpsertValidator->validate($input);
        $notify = Notify::find($id);
        if (empty($notify)) {
            return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
        }
        try {
            $this->model->upsert($input);
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("notifies.update-success", $notify->title)];
    }

    /**
     * @param $id
     * @return array|void
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $notify = Notify::find($id);
            if (empty($notify)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // Delete Notify
            $notify->delete();

            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("notifies.delete-success", $notify->title)];
    }
}