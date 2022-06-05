<?php

namespace App\V1\CMS\Controllers;

use App\MedicalSchedule;
use App\Profile;
use App\Supports\OFFICE_Email;
use App\Supports\OFFICE_SMS;
use App\User;
use SimpleSoftwareIO\QrCode\DataTypes\SMS;

use App\V1\CMS\Transformers\Week\WeekOfScheudelTransformer;
use App\V1\CMS\Transformers\Week\WeekOfScheudeOfUsersDoctorlTransformer;
use App\V1\CMS\Validators\WeekUpdateValidator;
use App\Week;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Controllers\BaseController;
use App\V1\CMS\Models\WeekModel;
use App\V1\CMS\Transformers\Week\WeekTransformer;
use App\V1\CMS\Validators\WeekCreateValidator;
//use App\V1\CMS\Validators\DepartmentUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Supports\WelcomeNotification;
use Illuminate\Support\Facades\File;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Matthewbdaly\SMS\Drivers\Nexmo;
use Matthewbdaly\SMS\Client;
class WeekController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new WeekModel();
    }

    public function search(Request $request, WeekOfScheudeOfUsersDoctorlTransformer $transformer)
    {
        $input = $request->all();
        $input['checkin'] = date('Y-m-d', time());
        $limit = array_get($input, 'limit', 100);
        $result = $this->model->searchWeek($input, [], $limit);
        Log::view($this->model->getTable(), $result);
        return $this->response->paginator($result, $transformer);
    }

    public function detailWeek($id, WeekTransformer $transformer)
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

    //lich kham bac si theo id bac si do
    public function userDoctor_week_scheuled($id, Request $request, WeekOfScheudeOfUsersDoctorlTransformer $transformer)
    {
        $input = $request->all();
        try {
            $input['user_doctor_id'] = $id;
            $input['checkout'] = date('Y-m-d', time());
            $limit = array_get($input, 'limit', 2);
            $result = $this->model->searchWeek($input, [], $limit);
            Log::view($this->model->getTable(), $result['data'] ?? 'Not exist in Database');
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->paginator($result, $transformer);
    }

    public function create(Request $request, WeekCreateValidator $createValidator, WeekTransformer $transformer)
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
        return $this->response->item($result, $transformer);
    }

    public function update($id, Request $request, WeekUpdateValidator $updateValidator, WeekTransformer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $updateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable(), $result->code);
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
            $result = Week::find($id);
            Log::delete($this->model->getTable(), $result->id);

            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            $medicalSchedule = MedicalSchedule::model()->where('week_id', $id)->get()->toArray();
            if (!empty($medicalSchedule)) {
                return $this->response->errorBadRequest(Message::get("V0032", "ID #$id"));
            }
            $result->delete();
            DB::commit();

        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->id)];
    }

    public function test(Request $request){
        $input = $request->all();
        $y = date('Y', time());
        $m = date("m", time());
        $d = date("d", time());
        $profileDr= Profile::model()->where('user_id', '=', $input['phone'])->first();
        $img = $profileDr['avatar'];
        $partSaveProfile = str_replace(",", '/', "$y/03/13/604C722EEF077.png");
        $path = public_path('uploads');
        $filePath = "$path/$partSaveProfile";
        File::delete($filePath);

        print_r($filePath); die;
        $user = User::find($input['phone']);
        $userCheck = User::where('username', $input['phone'])->first();
        $verify_code = mt_rand(100000, 999999);
        $param = [
            'verify_code' => $verify_code,
            'expired_code' => date('Y-m-d H:i:s', strtotime("+5 minutes")),
        ];

        if (!empty($userCheck)) {
            $userCheck->update($param);
            $paramSendMail = [
                'username' => $userCheck->username,
                'verify_code' => $verify_code,
            ];
            //print_r($paramSendMail['username']); die;
            $content = 'Ma xac thuc cua ban la '.$paramSendMail['verify_code'].'. Hieu luc ma nay chi co 5 phut';
            //OFFICE_SMS::sendSMS($paramSendMail['username'], $content);
           // OFFICE_Email::send('mail_send_update_profile_patient', $userCheck->email, $paramSendMail);
            return ['status' => Message::get("users.mail_send_update_profile_patient")];
        } else {
            return ["message" => 'So dien thoai khong ton tai'];
        }
    }
}