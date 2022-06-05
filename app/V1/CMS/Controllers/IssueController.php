<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 9:39 AM
 */

namespace App\V1\CMS\Controllers;

use App\File;
use App\Issue;
use App\Jobs\SendMailCreateIssueJob;
use App\Jobs\SendMailIssueDeadlineJob;
use App\OFFICE;
use App\Profile;
use App\Supports\Log;
use App\V1\CMS\Models\IssueModel;
use App\V1\CMS\Models\NotifyModel;
use App\V1\CMS\Traits\OrderTrait;
use App\V1\CMS\Traits\ReportTrait;
use App\V1\CMS\Transformers\Issue\IssueTransformer;
use App\V1\CMS\Transformers\Issue\IssueUserTransformer;
use App\V1\CMS\Validators\IssueCreateValidator;
use App\V1\CMS\Validators\IssueUpdateValidator;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class IssueController
 * @package App\V1\CMS\Controllers
 */
class IssueController extends BaseController
{
    use OrderTrait;
    use ReportTrait;

    /**
     * @var IssueModel
     */
    protected $model;

    /**
     * IssueController constructor.
     */
    public function __construct()
    {
        $this->model = new IssueModel();
    }

    /**
     * @param Request $request
     * @param IssueTransformer $issueTransformer
     * @return mixed
     */
    public function search(Request $request, IssueTransformer $issueTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable());
        return $this->response->paginator($result, $issueTransformer);
    }

    //Search Mine
    public function searchMine(Request $request, IssueTransformer $issueTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->searchMine($input, [], $limit);
        //  Log::view($this->model->getTable());
        return $this->response->paginator($result, $issueTransformer);
    }

    public function detail($id, IssueTransformer $issueTransformer)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            //   Log::view($this->model->getTable());
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

        return $this->response->item($result, $issueTransformer);
    }

    public function create(Request $request, IssueCreateValidator $issueCreateValidator, IssueTransformer $issueTransformer)
    {
        $input = $request->all();
        $issueCreateValidator->validate($input);
        if (!empty($input['progress'])) {
            if ($input['progress'] < 0 || $input['progress'] > 100) {
                throw new \Exception(Message::get("V017"));
            }
        }
        if (!empty($input['name']) && !empty($input['module_id']) && !empty($input['module_category_id'])) {
            $issue = Issue::join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->where('issues.name', "{$input['name']}")
                ->where('module_category.module_id', "{$input['module_id']}")
                ->where('issues.module_category_id', "{$input['module_category_id']}")
                ->get()->toArray();
            if (!empty($issue)) {
                return $this->response->errorBadRequest(Message::get("task.exist-fk", $input['name']));
            }
        }
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::create($this->model->getTable(), $input['name']);
//            $this->createNotify($result);
            $userNameCreate = OFFICE::getCurrentUserName();
            $userIds = array_pluck($result->user_id, 'id');
            foreach ($userIds as $id) {
                $profileUserAssigned = Profile::model()->where('user_id', $id)->first();
                $userNameAssigned = Arr::get($profileUserAssigned, 'full_name', null);
                $email = Arr::get($profileUserAssigned, 'email', null);
                if ($email) {
                    $data = [
                        'logo' => env('APP_LOGO'),
                        'title' => $input['name'],
                        'kpi_name' => $userNameCreate,
                        'member' => $userNameAssigned,
                        'priority' => ISSUE_PRIORITY_NAME[$result->priority],
                        'deadline' => date('d-m-Y H:i', strtotime($result->deadline)),
                        'link_to' => 'http://k-office.kpis.vn/#/issues/' . $result->id,
                    ];
                    $this->dispatch(new SendMailCreateIssueJob($email, $data));
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($result, $issueTransformer);
    }

    public function createNotify(Issue $issue)
    {
        $now = time();
        $data = [
            'issue_id' => $issue->id,
            'title' => "tạo",
            'sender' => $issue->created_by,
            'receiver' => $issue->user_id,
            'description' => $issue->description,
            'date' => date("Y-m-d H:i:s", $now),
            'is_active' => 1,
        ];
        $notifies = new NotifyModel();
        $notifies->create($data);
    }

    public function update(
        $id,
        Request $request,
        IssueUpdateValidator $issueUpdateValidator,
        IssueTransformer $issueTransformer
    )
    {
        $input = $request->all();
        $input['id'] = $id;
        $issueUpdateValidator->validate($input);
        if (!empty($input['progress'])) {
            if ($input['progress'] < 0 || $input['progress'] > 100) {
                throw new \Exception(Message::get("V017"));
            }
        }
        try {
            DB::beginTransaction();
            if (!empty($input['status']) === 'COMPLETED' || !empty($input['status']) === 'OPENED') {
                $role = OFFICE::getCurrentRoleCode();
                if ($role != 'ADMIN' || $role != 'Manager') {
                    throw new AccessDeniedHttpException(Message::get("no_permission"));
                }
            }
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable(), $result->name);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($result, $issueTransformer);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = Issue::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // 1. Delete Issue
            $result->delete();
            Log::delete($this->model->getTable(), $result->name);
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("task.delete-success", $result->name)];
    }

    public function promptIssue()
    {
        $now = Carbon::now()->toDateString();
        $user_id_login = OFFICE::getCurrentUserId();
        $user_name_login = OFFICE::getCurrentUserName();
        $profileUserAssigned = Profile::model()->where('user_id', $user_id_login)->get()->first();
        $email = $profileUserAssigned->email;
        $issue = Issue::where('user_id', $user_id_login)
            ->whereDate('deadline', '>', $now)
            ->where(DB::raw(strtotime('deadline') - strtotime($now)), '<=', 2)
            ->where('is_prompt', '=', 0)
            ->get()->toArray();
        if (empty($issue)) {
            return ["data" => []];
        }
        $count = 0;
        foreach ($issue as $item) {
            $input['id'] = $item['id'];
            $input['is_prompt'] = 1;
            $this->model->update($input);
            $count++;
        }
        $data = [
            'total_issue' => $count,
            'user_assign' => $user_name_login,
            'link_to' => 'http://k-office.kpis.vn/#/issues'
        ];
        if ($email) {
            $this->dispatch(new SendMailIssueDeadlineJob($email, $data));
        }
    }

    public function issueUser(Request $request, IssueUserTransformer $issueUserTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable());
        return $this->response->paginator($result, $issueUserTransformer);
    }

    public function exportIssueUser(Request $request)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 1000);
        $result = $this->model->search($input, [], $limit);
        try {
            $filter = [];
            if (!empty($input['from'])) {
                $filter['from'] = [">=" => $input['from'] . " 00:00:00"];
            }
            if (!empty($input['to'])) {
                $filter['to'] = ["<=" => $input['to'] . " 23:59:59"];
            }
            if (empty($input['user_id']) && empty($input['full_name'])) {
                $input['user'] = "Tất cả nhân viên";
            }
            if (!empty($input['user_id'])) {
                $user_name = Profile::where('user_id', $input['user_id'])->first();
                $input['user'] = $user_name->full_name;
            }
            if (!empty($input['full_name'])) {
                $input['user'] = $input['full_name'];
            }
            $date = date('YmdHis', time());
            $i = 0;
            $total = 0;
            foreach ($result as $key => $item) {
                $description = new \Html2Text\Html2Text($item->description);
                $dataIssueUser = [
                    'stt' => ++$i,
                    'name' => $item->name,
                    'created_at' => date('d/m/Y', strtotime($item->created_at)),
                    'deadline' => !empty($item->deadline) ? date('d-m-Y',
                        strtotime($item->deadline)) : null,
                    'estimated_time' => $item->estimated_time,
                    'link' => 'http://k-office.kpis.vn/#/issues/' . $item->id,
                    'description' => $description->getText(),
                ];
                $total += $dataIssueUser['estimated_time'];
                $dataPrint[] = array_values($dataIssueUser);
            }

            $input['total'] = (float)$total;
            $input['dataTable'] = $dataPrint;
            $this->writeExcelIssueUserReport("IssueUser_$date", storage_path('Export') . "/IssueUser", $input);
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response["message"]);
        }
    }
}