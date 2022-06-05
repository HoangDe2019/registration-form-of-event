<?php

namespace App\V1\CMS\Controllers;

use App\Department;
use App\MedicalSchedule;
use App\Profile;
use App\Role;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\User;
use App\V1\CMS\Controllers\BaseController;
use App\V1\CMS\Models\MedicalScheduleModel;
use App\V1\CMS\Transformers\MedicalShedule\MedicalSheduleTransformer;
use App\V1\CMS\Transformers\MedicalShedule\MedicalSheduleWeekUsersTransformer;
use App\V1\CMS\Transformers\Week\WeekTransformer;
use App\V1\CMS\Validators\MedicalScheduleCreateValidator;
use App\V1\CMS\Validators\MedicalScheduleUpdateValidator;
use App\V1\CMS\Validators\WeekCreateValidator;
use App\V1\CMS\Validators\WeekUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MedicalScheduleController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new MedicalScheduleModel();
    }

    public function search(Request $request, MedicalSheduleTransformer $transformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->searchUserWeekHaveSchedules($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function searchWeekofUsers(Request $request, MedicalSheduleWeekUsersTransformer $transformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function searchWeekofUsersId($id, Request $request, MedicalSheduleWeekUsersTransformer $transformer)
    {
        try{
            $user_profile = User::model()->where('id', $id)->first();
            $department = Department::model()->where('id', $user_profile['department_id'])->first();
            $role = Role::model()->where('id', $user_profile['role_id'])->first();
            $profile = Profile::model()->where('user_id', $user_profile['id'])->first();
            $schedule = MedicalSchedule::model()->where('user_id', $user_profile['id'])
                ->join('week','week.id', '=','medical_schedules.week_id')
                ->groupBy('week.id','week.checkin', 'week.checkout')
                ->select([
                    'week.id as id',
                    'week.checkin as start',
                    'week.checkout as end',
                ])->get()->toArray();
            $result = [
                'id'=>$user_profile['id'],
                'full_name'=>$profile['full_name'],
                'job'=>$role['name'],
                'department' => $department['name'],
                'week'=>$schedule
            ];
        }catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return ['status' => Response::HTTP_OK, 'data' => $result];
    }

    public function detailMedicalSchedule($id, MedicalSheduleTransformer $transformer)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
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
        return $this->response->item($result, $transformer);
    }

    public function create(Request $request, MedicalScheduleCreateValidator $createValidator, MedicalSheduleTransformer $transformer)
    {
        $input = $request->all();
        $createValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::create($this->model->getTable(), $result->id);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($result, $transformer);
    }

    public function update($id, Request $request, MedicalScheduleUpdateValidator $updateValidator, MedicalSheduleTransformer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $updateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable());
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
            $result = MedicalSchedule::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            $result->delete();
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("{} R003", $result->code)];
    }
}