<?php

namespace App\V1\CMS\Controllers;

use App\DiseasesDiagnosed;
use App\HealthBookRecord;
use App\MedicalHistory;
use App\OFFICE;
use App\Prescription;
use App\PrescriptionDetail;
use App\Profile;
use App\Role;
use App\Supports\Log;
use App\Supports\OFFICE_Email;
use App\Supports\OFFICE_Error;
use App\Supports\Message;
use App\Supports\OFFICE_SMS;
use App\User;
use App\V1\CMS\Exports\InvoicesExport;
use App\V1\CMS\Exports\UserExports;
use App\V1\CMS\Models\ProfileModel;
use App\V1\CMS\Models\UserModel;
use App\V1\CMS\Transformers\HealthBookRecord\HealthBookRecordTransformer;
use App\V1\CMS\Transformers\User\UserCustomerDetailProfileSchudelTransformer;
use App\V1\CMS\Transformers\User\UserCustomerDetailProfileTransformer;
use App\V1\CMS\Transformers\User\UserCustomerProfileTransformer;
use App\V1\CMS\Transformers\User\UserPatientDetailProfileTransformer;
use App\V1\CMS\Transformers\User\UserProfileTransformer;
use App\V1\CMS\Transformers\User\UserTransformer;
use App\V1\CMS\Validators\UserChangePasswordValidator;
use App\V1\CMS\Validators\UserCreateValidator;
use App\V1\CMS\Validators\UserDoctorCreateValidator;
use App\V1\CMS\Validators\UserDoctorUpdateValidator;
use App\V1\CMS\Validators\UserProfileUpdateValidator;
use App\V1\CMS\Validators\UserUpdateProfileDoctorValidator;
use App\V1\CMS\Validators\UserUpdateValidator;
use FontLib\OpenType\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use League\Flysystem\Config;
use Dingo\Api\Http\Response\Factory;
use Maatwebsite\Excel\Excel;
use Mpdf\Mpdf;

/**
 * Class UserController
 *
 * @package App\V1\CMS\Controllers
 */
class UserController extends BaseController
{

    /**
     * @var UserModel
     */
    protected $model;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * @param UserTransformer $userTransformer
     * @return \Dingo\Api\Http\Response
     */
    public function search(Request $request, UserTransformer $userTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable());
        return $this->response->paginator($result, $userTransformer);
    }

    public function view($id, UserTransformer $userTransformer)
    {
        $user = User::where(['id' => $id])->first();
        Log::view($this->model->getTable());
        return $this->response->item($user, $userTransformer);
    }

    public function info(UserProfileTransformer $userProfileTransformer)
    {
        $id = OFFICE::getCurrentUserId();
        $user = User::where(['id' => $id])->first();        //  Log::view($this->model->getTable());
        if (empty($user)) {
            return $this->response->errorBadRequest(Message::get('V003', "ID #$id"));
        }
        return $this->response->item($user, $userProfileTransformer);
    }

    public function getInfo(Request $request, UserTransformer $userTransformer)
    {
        $input = $request->all();
        try {
            $userId = OFFICE::getCurrentUserId();
            $user = User::where(['id' => $userId])->first();
            $roleCode = OFFICE::getCurrentRoleCode();
            $roleName = OFFICE::getCurrentRoleName();
            $profile = Profile::where(['user_id' => $userId])->first();
            $avatar = !empty($profile['avatar']) ? url('/v2') . "/img/uploads," . str_replace('/', ',', $profile['avatar']) : null;
            // Get Employee
            $result = [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'is_super' => $user->is_super,
                'role_code' => $roleCode,
                'last_name' => $profile['last_name'],
                'full_name' => $profile['full_name'],
                'first_name' => $profile['first_name'],
                'genre' => config('constants.STATUS.GENRE')
                [strtoupper(object_get($user, "profile.genre", 'O'))],
                'date_birth' => date('d/m/Y', strtotime($profile['birthday'])),
                'blood_group' => object_get($input, 'blood_group', null),
                'address' => $profile['address'],
                'phone' => $profile['phone'],
                'avatar' => $avatar,
                'role_name' => $roleName,
            ];
            // Show Permissions
            if (!empty($input['permissions']) && $input['permissions'] == 1) {
                $result['permissions'] = OFFICE::getCurrentPermission();
            }
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Response::HTTP_OK, 'data' => $result];
    }

    public function searchDoctors(Request $request, UserCustomerProfileTransformer $userTransformer)
    {
        $input = $request->all();
        $input['role_code'] = 'doctor';
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable());
        return $this->response->paginator($result, $userTransformer);
    }

    public function searchDoctorsAuthorize(Request $request, UserCustomerProfileTransformer $userTransformer)
    {
        $input = $request->all();
        $input['role_code_not_equal'] = 'patients';
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        Log::view($this->model->getTable());
        return $this->response->paginator($result, $userTransformer);
    }

    public function viewDoctorsAuthorize($id, UserCustomerProfileTransformer $userTransformer)
    {
        $user = User::where(['id' => $id])->first();
        Log::view($this->model->getTable());
        return $this->response->item($user, $userTransformer);
    }

    public function create(Request $request, UserCreateValidator $userCreateValidator, UserTransformer $userTransformer)
    {
        $input = $request->all();
        $userCreateValidator->validate($input);
        try {
            DB::beginTransaction();
            $user = $this->model->upsert($input);
            Log::create($this->model->getTable(), $user->code);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($user, $userTransformer);
    }

    public function createNewDoctor(Request $request, UserDoctorCreateValidator $userCreateValidator, UserTransformer $userTransformer)
    {
        $input = $request->all();
        $userCreateValidator->validate($input);
        try {
            DB::beginTransaction();
            $user = $this->model->upsertDoctorCreateOrUpdate($input);
            Log::create($this->model->getTable(), $user->code);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
      //  return $this->response->item($user, $userTransformer);
      return ['status' => Message::get("R001", $user->username)];
    }

    public function update(
        $id,
        Request $request,
        UserUpdateValidator $userUpdateValidator,
        UserTransformer $userTransformer
    )
    {
        $input = $request->all();
        $input['id'] = $id;
        $userUpdateValidator->validate($input);
        try {
            DB::beginTransaction();
            $user = $this->model->upsert($input);
            Log::update($this->model->getTable(), $user->code);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($user, $userTransformer);
    }

    //Cap nhat thong tin cua bac si
    public function updateUserDoctors(
        $id,
        Request $request,
        UserDoctorUpdateValidator $userUpdateValidator,
        UserTransformer $userTransformer
    )
    {
        $input = $request->all();
        $input['id'] = $id;
        $userUpdateValidator->validate($input);
        try {
            DB::beginTransaction();
            $user = $this->model->upsertDoctorCreateOrUpdate($input);
            Log::update($this->model->getTable(), $user->email);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R002", $user->username)];
    }

    public function updateprofile(Request $request, UserUpdateValidator $userUpdateValidator, UserTransformer $userTransformer)
    {
        $input = $request->all();
        $input['id'] = OFFICE::getCurrentUserId();
        $userUpdateValidator->validate($input);
        try {
            DB::beginTransaction();
            $user = $this->model->upsertInfo($input);
            Log::update($this->model->getTable(), $user->code);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R002", $user->username)];
    }

    public function updateprofileDoctor(Request $request, UserUpdateProfileDoctorValidator $userUpdateValidator, UserTransformer $userTransformer)
    {
        $input = $request->all();
        $input['id'] = OFFICE::getCurrentUserId();
        $userUpdateValidator->validate($input);
        try {
            DB::beginTransaction();
            $user = $this->model->upsertDoctorInfo($input);
            Log::update($this->model->getTable(), $user->code);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R002", $user->username)];
    }

    /**
     * @param $id
     * @return array|void
     */

    public function delete_userDoctors($id)
    {
        try {
            DB::beginTransaction();
            $result = User::model()->find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // 1. Delete Profile
            if ($result->profile) {
                $result->profile->delete();
            }

            // 1. Delete Company
            $result->deleted = 1;
            $result->updated_at = date('Y-m-d H:i:s', time());
            $result->updated_by = OFFICE::getCurrentUserId();
            $result->deleted_at = date('Y-m-d H:i:s', time());
            $result->deleted_by = OFFICE::getCurrentUserId();
            $result->save();
            Log::delete($this->model->getTable(), $result->name);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->code)];
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $user = User::model()->where('id', $id)->first();
            if (empty($user)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // 1. Delete Profile
            if ($user->profile) {
                $user->profile->delete();
            }
            // 2. Delete User
            $user->deleted = 1;
            $user->updated_at = date('Y-m-d H:i:s', time());
            $user->updated_by = OFFICE::getCurrentUserId();
            $user->deleted_at = date('Y-m-d H:i:s', time());
            $user->deleted_by = OFFICE::getCurrentUserId();
            $user->save();
            Log::delete($this->model->getTable(), $user->code);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $user->code)];
    }

    public function changePassword(Request $request, UserChangePasswordValidator $userChangePasswordValidator)
    {
        $input = $request->all();
        $user_id = OFFICE::getCurrentUserId();
        $company_id = OFFICE::getCurrentCompanyId();
        $userChangePasswordValidator->validate($input);
        try {
            DB::beginTransaction();
            $user = User::where([
                'id' => $user_id,
            ])->first();
            // Change password
            if (!password_verify($input['password'], $user->password)) {
                throw new \Exception(Message::get("V002", Message::get("password")));
            }
            $user->password = password_hash($input['new_password'], PASSWORD_BCRYPT);
            $user->save();
            Log::update($this->model->getTable());
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("users.change-password")];
    }

    public function active($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            return $this->response->errorBadRequest(Message::get("users.not-exist", "#$id"));
        }
        try {
            DB::beginTransaction();
            if ($user->is_active === 1) {
                $msgCode = "users.inactive-success";
                $user->is_active = "0";
            } else {
                $user->is_active = "1";
                $msgCode = "users.active-success";
            }
            // Write Log
            Log::update($this->model->getTable(), $user->is_active);
            $user->save();
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest(Message::get($msgCode, $user->phone));
        }
        return ['status' => Message::get($msgCode, "Product")];
    }

    public function exportUser(Request $request)
    {
        $input = $request->all();
        try {
            $fromStart = empty($input['month_begin']) ? date('Y-m-d', time()): $input['month_begin'];
            $toEnd = empty($input['month_end']) ? date('Y-m-d', time()): $input['month_end'];
            $phone_number = empty($input['phone_number']) ? null : $input['phone_number'];
            $health_record_books = [];
            if(empty($phone_number)) {
                $health_record_books = User::model()->whereNull('users.deleted_at')
                    ->where('health_record_books.action', '>=', $fromStart)
                    ->where('health_record_books.action', '<=', $toEnd)
                    ->where('roles.id', '=', 5)
                    ->join('health_record_books', 'health_record_books.user_id', '=', 'users.id')
                    ->join('profiles', 'profiles.user_id', '=', 'users.id')
                  //  ->join('departments', 'departments.id', '=', 'users.department_id')
                    ->join('roles', 'roles.id', '=', 'users.role_id')
                    ->get()->toArray();
            }else{
                $health_record_books = User::model()->whereNull('users.deleted_at')
                    ->where('health_record_books.action', '>=', $fromStart)
                    ->where('health_record_books.action', '<=', $toEnd)
                    ->where('profiles.phone', 'like', "%{$phone_number}%")
                    ->where('roles.id', '=', 5)
                    ->join('health_record_books', 'health_record_books.user_id', '=', 'users.id')
                    ->join('profiles', 'profiles.user_id', '=', 'users.id')
                 //   ->join('departments', 'departments.id', '=', 'users.department_id')
                    ->join('roles', 'roles.id', '=', 'users.role_id')
                    ->get()->toArray();
            }
            $health_record_books = array_map(function ($pre) {
                $role_name = Role::find($pre['role_id']);
                return [
                    'name_doctor' => $pre['full_name'],
                    'role' => $role_name->name,
                    'phone_number' => $pre['phone'],
                    'email' => $pre['email'],
                    'address' => $pre['address'],
                    'birthday' => $pre['birthday'],
                    'username' => $pre['username'],
                    'active' => $pre['action']
                ];
            }, $health_record_books);
           // print_r($health_record_books); die;



          //  $profilePatients = Profile::model()->where(['user_id' => OFFICE::getCurrentUserId()])->WhereNull('deleted_at')->first();

            $data = [
                'from' => $fromStart,
                'to'=>$toEnd,
              //  'full_name'=>$profilePatients->full_name,
                'number_HRB' => $health_record_books
            ];

            $dateFile = date('d-m-Y', time());
            $nameFile = $fromStart;
            $covertFileNameToEN = $this->convert_vi_to_en($nameFile);
            //
            $filenameFormat = str_replace(' ', '-', $covertFileNameToEN);
            $filename = "{$filenameFormat}_{$dateFile}";

            $this->downloadConvertPDF($data, $filename, 'reportPDF', $fromStart);

            //return (new UserExports())->download('invoices.xlsx');
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return ['status' => Message::get("ok")];
        }

    }

    //  Khoi phuc mat khau
    public function resetPassword(Request $request)
    {
        $input = $request->all();
        $userCheck = User::where('email', $input['email'])
            ->orWhere('username', $input['email'])->first();
        $verify_code = mt_rand(100000, 999999);
        $param = [
            'verify_code' => $verify_code,
            'expired_code' => date('Y-m-d H:i:s', strtotime("+5 minutes")),
        ];
        if (!empty($userCheck)) {
            $userCheck->update($param);

            $paramSendMail = [
                'name' => $userCheck->profile->full_name,
                'username' => $userCheck->username,
                'verify_code' => $verify_code,
            ];
            OFFICE_Email::send('mail_send_reset_password', $userCheck->email, $paramSendMail);
            $content = 'Mã xác thực của bạn là '.$paramSendMail['verify_code'].'. Hiệu lực của mà này chỉ có 5 phút';
            //OFFICE_SMS::sendSMS($paramSendMail['username'], $content);
            return ['status' => Message::get("users.reset-password-success")];
        } else {
            return ["message" => 'Số điện thoại hay email của bạn không tồn tại!'];
        }
    }

    //Xac thuc ma code de tao mat kau moi
    public function verifyCode(Request $request)
    {
        $input = $request->all();
        $userCheck = User::where('verify_code', $input['verify_code'])
            ->where('expired_code', '>=', date("Y-m-d H:i:s", time()))->first();
        if (!empty($userCheck)) {
            $userCheck->password = password_hash($input['new_password'], PASSWORD_BCRYPT);
            $userCheck->save();
            return ['status' => Message::get("users.reset-password-success")];
        }
    }

    //-------------------Benh Nhan-------------------------
    //Benh nhan
    //Tim kiem thong tin bac si cho benh nhan
    public function searchbyPatient(Request $request, UserCustomerProfileTransformer $userPatient)
    {
        $input['role_code'] = "doctor";
        $limit = array_get($input, 'limit', 8);
        $result = $this->model->search($input, [], $limit);
        // Log::view($this->model->getTable());
        return $this->response->paginator($result, $userPatient);
    }

    //Thong tin chi tiet cua bac si xem boi benh nhan
    public function detaildoctorsbyPatient($id, Request $request, UserCustomerDetailProfileTransformer $userPatient)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            if (empty($result)) {
                return ["data" => ['KhÃ´ng tÃ¬m tháº¥y thÃ´ng tin nÃ y!']];
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

    // Chi tiet benh nhan duoc xem boi bac si
    //Thong tin chi tiet cua bac si xem boi benh nhan
    public function detailPatientbyDoctors($id, Request $request, UserPatientDetailProfileTransformer $userPatient)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            if (empty($result)) {
                return ["data" => ['KhÃ´ng tÃ¬m tháº¥y thÃ´ng tin nÃ y!']];
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
    //end

    public function fillter(Request $request, UserCustomerDetailProfileTransformer $userPatient)
    {
        try {
            $input['role_code'] = "doctor";
            $credentials = $request->only('department_code', 'genre', 'role_code');

            $limit = array_get($credentials, 'limit', 20);
            $result = $this->model->fillter($credentials, [], $limit);
            if (empty($result)) {
                return ["data" => ['KhÃ´ng tÃ¬m tháº¥y thÃ´ng tin nÃ y!']];
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        // Log::view($this->model->getTable());
        return $this->response->paginator($result, $userPatient);
    }

    //*Dang ky thong tin  chua co kham lan nao
    public function userofSchdule($id, Request $request, UserCustomerDetailProfileSchudelTransformer $userPatient)
    {
        try {
            $result = $this->model->getFirstBy('id', $id);
            if (empty($result)) {
                return ["data" => ['KhÃ´ng tÃ¬m tháº¥y thÃ´ng tin nÃ y!']];
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

    //Dang ky thong tin da co kham lan dau tien
    public function sendEmailBeforeUpdateAccount(Request $request)
    {
        $input = $request->all();
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
            OFFICE_Email::send('mail_send_update_profile_patient', $userCheck->email, $paramSendMail);
			$content = 'Mã xác thực của bạn là '.$paramSendMail['verify_code'].'. Hiệu lực của này chỉ có 5 phút';
            //OFFICE_SMS::sendSMS($paramSendMail['username'], $content);
            return ['status' => Message::get("users.mail_send_update_profile_patient")];
        } else {
            return ["message" => 'So dien thoai khong ton tai'];
        }
    }

    public function updatePatient(Request $request)
    {
        $input = $request->all();
        $userCheck = User::where('verify_code', $input['verify_code'])
            ->where('expired_code', '>=', date("Y-m-d H:i:s", time()))->first();
        if (!empty($userCheck)) {
            $userCheck->username = empty($input['username']) ? array_get($input, 'username', $userCheck->username) : $input['username'];
            $userCheck->password = password_hash($input['password'], PASSWORD_BCRYPT);
            Log::update($this->model->getTable(), $userCheck->username);
            $userCheck->save();
            $content = 'Tài khoản '.$input['username'].'. Đã tạo tài khoản mới thành công';
            //OFFICE_SMS::sendSMS($input['username'], $content);

            return ['status' => Message::get("users.create-account")];
        }else{
            $response = $input['verify_code'];
            return $this->response->errorBadRequest(Message::get("users.not-exist", "#$response"));
        }
    }

    //-------------------------The end Benh nhan-------------------------------------------
    public function cityDfs(Request $request)
    {
        $input = $request->all();
        try {
            $path = public_path('dataJSON');
            $filePath1 = "$path/city.json";
            header('Access-Control-Allow-Origin: *');
            header("Content-Type:application/json");
//            header("Content-Disposition:attachment; filename='$fileName'");
            readfile($filePath1);
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
    }


    public function jsonCity_Dictrict_Ward(Request $request)
    {
        $input = $request->all();
        try {
            $path = public_path('dataJSON');
            $filePath1 = "$path/data.json";
            header('Access-Control-Allow-Origin: *');
            header("Content-Type:application/json");
//            header("Content-Disposition:attachment; filename='$fileName'");
            readfile($filePath1);
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
    }

    public function downloadConvertPDF($data, $file_name = null, $file_path = null, $user_profile = null)
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4'
        ]);
        $html = (string)view('pdf.report_pdf', $data);
        $mpdf->WriteHTML($html);
        $fileName = $file_name . ".pdf";
        $y = date('Y', time());
        $m = date("m", time());
        $d = date("d", time());
        $path = public_path($file_path);
        if (!file_exists("$path/$user_profile/$y/$m/$d")) {
            mkdir("$path/$user_profile/$y/$m/$d", 0777, true);
        }
        $filePath = "$path/$user_profile/$y/$m/$d/$fileName";
        file_put_contents($filePath, $fileName);
        $mpdf->Output($filePath, 'F');
        header('Access-Control-Allow-Origin: *');
        header("Content-Type:application/pdf");
        header("Content-Disposition:attachment; filename={$fileName}");
        readfile($filePath);
        \Illuminate\Support\Facades\File::delete($filePath);
    }

    //Covert VietNamese to EngLish
    public function convert_vi_to_en($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
        $str = preg_replace("/(Đ)/", "D", $str);
        //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
        return $str;
    }

}
