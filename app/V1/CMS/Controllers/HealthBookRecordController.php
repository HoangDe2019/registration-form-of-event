<?php

namespace App\V1\CMS\Controllers;


use App\Supports\OFFICE_Email;
use App\User;
use App\Supports\Log;
use App\Supports\OFFICE_SMS;
use App\Supports\OFFICE_Error;
use App\Supports\Message;
use App\V1\CMS\Models\HealthBookRecordModel;
//use App\V1\CMS\Models\UserModel;
use App\V1\CMS\Transformers\HealthBookRecord\HealthBookRecordTransformer;
use App\V1\CMS\Transformers\User\UserTransformer;
use App\V1\CMS\Validators\UserCreateValidator;
use App\V1\CMS\Validators\HealthBookRecordUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * Class UserController
 *
 * @package App\V1\CMS\Controllers
 */
class HealthBookRecordController extends BaseController
{

    /**
     * @var HealthBookRecordModel
     */
    protected $model;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->model = new HealthBookRecordModel();
    }

    /**
     * @param UserTransformer $userTransformer
     * @return \Dingo\Api\Http\Response
     */

    public function searchPatient(Request $request, HealthBookRecordTransformer $userPatient)
    {
        $input = $request->all();
        $input['role_code'] = "5";
        $limit  = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        return $this->response->paginator($result, $userPatient);
    }

    //Thong tin chi tiet cua bac si xem boi benh nhan
    public function detaildoctorsbyPatient($id, Request $request, HealthBookRecordTransformer $userPatient)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            if (empty($result)) {
                return ["data" => ['Không tìm thấy thông tin này!']];
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->item($result, $userPatient);
    }

    //*Dang ky thong tin  chua co kham lan nao
    public function createPatients(Request $request, UserCreateValidator $userCreateValidator, UserTransformer $userTransformer)
    {
        $input = $request->all();
        $userCreateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            $user = User::find($result->user_id);
            Log::create($this->model->getTable(), $user->email);
            DB::commit();
            $userCheck = User::where('email', $input['email'])->first();
            if (!empty($userCheck)) {
                $paramSendMail = [
                    'name'=>$userCheck->profile->full_name,
                    'phone'=>$userCheck->profile->phone,
                    'address'=>$userCheck->profile->address,
                    'username' => $userCheck->username
                ];
                OFFICE_Email::send('mail_send_create_account_patients', $userCheck->email, $paramSendMail);
                $content = 'Tài khoản của bạn đã được đăng ký thành công với số điện thoại'. $userCheck->username.'. đã đăng ký để làm tài khoản';
                //OFFICE_SMS::sendSMS($userCheck->profile->phone, $content);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R001", $result->id)];
    //    return $this->response->item($user, $userTransformer);
    }

    public function updatePatients(
        $id,
        Request $request,
        HealthBookRecordUpdateValidator $userUpdateValidator,
        HealthBookRecordTransformer $userTransformer
    )
    {
        $input = $request->all();
        $input['id'] = $id;
        $userUpdateValidator->validate($input);
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
        return $this->response->item($result, $userTransformer);
    }
//-------------------------The end Benh nhan-------------------------------------------
}
