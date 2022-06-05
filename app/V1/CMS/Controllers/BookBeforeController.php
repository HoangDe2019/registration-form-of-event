<?php

namespace App\V1\CMS\Controllers;

use App\OFFICE;
use App\BookBefore;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Email;
use App\Supports\OFFICE_SMS;
use App\User;
use App\V1\CMS\Transformers\BookBefore\BookBeforeDoctorsTransformer;
use App\V1\CMS\Transformers\BookBefore\BookBeforePatientsTransformer;
use App\V1\CMS\Validators\BookBeforeUpdateValidator;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\BookBeforeModel;
use App\V1\CMS\Transformers\BookBefore\BookBeforeTransformer;
use App\V1\CMS\Validators\BookBeforeCreateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Predis\Response\Status;

class BookBeforeController extends BaseController
{
    /**
     * @var mixed
     */
    protected $model;

    public function __construct()
    {
        $this->model = new BookBeforeModel();
    }

    public function search(Request $request, BookBeforeTransformer $transformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->searchBooksBefore($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function getinfoBookBefore(Request $request, BookBeforePatientsTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        $input['user_id'] = $userId;
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->search($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function getinfoBookBeforeDoctor(Request $request, BookBeforeDoctorsTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        $input['user_doctor'] = $userId;
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->fillter($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function getinfoBookBeforeofPatienAndDortor($id, Request $request, BookBeforePatientsTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        $input['user_doctor'] = $userId;
        $input['user_patients'] = $id;
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->fillter($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function getinfoBookBeforeofPatienAndDortorNear($id, Request $request, BookBeforePatientsTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        $input['user_doctor'] = $userId;
        $input['user_patients'] = $id;
        $input['state'] = 1;
        $limit = array_get($input, 'limit', 2);
        $result = $this->model->fillter($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function create(Request $request, BookBeforeCreateValidator $createValidator, BookBeforeTransformer $transformer)
    {
        $input = $request->all();
        $createValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::delete($this->model->getTable(), $result->id);
            DB::commit();
            $user_id = OFFICE::getCurrentUserId();
            $userCheck = User::where(['id' => $user_id])->first();
            $bookBefore = BookBefore::where([
                'user_id' => $user_id,
                'times_of_day_id' => $input['options_time']
            ])
                ->first();
            if (!empty($userCheck) && !empty($bookBefore)) {
                $paramSendMail = [
                    'name' => $userCheck->profile->full_name,
                    'phone' => $userCheck->profile->phone,
                    'address' => $userCheck->profile->address,
                    'doctor_name' => $bookBefore->schedule->user->profile->full_name,
                    'date_work' => date('d-m-Y', strtotime($bookBefore->schedule->checkin)),
                    'session' => $bookBefore->schedule->session,
                    'book_time' => $bookBefore->time
                ];
                OFFICE_Email::send('mail_send_booking_patients', $userCheck->email, $paramSendMail);
                $content = 'Bệnh nhân'.$userCheck->profile->full_name.', số điện thoại:'.$userCheck->profile->phone.', địa chỉ '.$userCheck->profile->address.', đặt lịch hẹn với bác sĩ '.$bookBefore->schedule->user->profile->full_name.' vào lúc '.date('d-m-Y', strtotime($bookBefore->schedule->checkin)).''.$bookBefore->time.'-'.$bookBefore->schedule->session.'. Đang chờ duyệt';
                //OFFICE_SMS::sendSMS($userCheck->profile->phone, $content);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => "Xác nhận đăng ký thành công. Mọi thông tin chúng tôi đã gửi qua địa chỉ email của bạn"];
        //        return $this->response->item($result, $transformer);
    }

    public function update($id, Request $request, BookBeforeUpdateValidator $updateValidator, BookBeforeTransformer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $input['state'] = 1;
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable());
            $bookBefore_id = $id;
            $bookBefore = BookBefore::where([
                'id' => $bookBefore_id,
            ])->first();
            $userCheck = User::where(['id' => $bookBefore['user_id']])->first();
            if (!empty($userCheck) && !empty($bookBefore)) {
                $paramSendMail = [
                    'name' => $userCheck->profile->full_name,
                    'phone' => $userCheck->profile->phone,
                    'address' => $userCheck->profile->address,
                    'doctor_name' => $bookBefore->schedule->user->profile->full_name,
                    'date_work' => date('d-m-Y', strtotime($bookBefore->schedule->checkin)),
                    'session' => $bookBefore->schedule->session,
                    'book_time' => $bookBefore->time
                ];
                //  print_r($userCheck->email); die;
                OFFICE_Email::send('mail_send_booking_patients_confirm', $userCheck->email, $paramSendMail);
                $content = 'Bệnh nhân'.$userCheck->profile->full_name.', số điện thoại:'.$userCheck->profile->phone.', địa chỉ '.$userCheck->profile->address.', đặt lịch hẹn với bác sĩ '.$bookBefore->schedule->user->profile->full_name.' vào lúc '.date('d-m-Y', strtotime($bookBefore->schedule->checkin)).''.$bookBefore->time.'-'.$bookBefore->schedule->session.'. Đã được duyệt! Vui lòng đến sớm trước 30 phút';
                //OFFICE_SMS::sendSMS($userCheck->profile->phone, $content);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        //return $this->response->item($result, $transformer);
        return ['status' => Message::get("R019", $result->id)];
    }

    public function delete_refuse($id, Request $request, BookBeforeUpdateValidator $updateValidator, BookBeforeTransformer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        try {
            $input['state'] = 2;
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable());
            $bookBefore_id = $id;
            $bookBefore = BookBefore::where([
                'id' => $bookBefore_id,
            ])->first();
            $userCheck = User::where(['id' => $bookBefore['user_id']])->first();
            //print_r($userCheck); die;
            if (!empty($userCheck) && !empty($bookBefore)) {
                $paramSendMail = [
                    'name' => $userCheck->profile->full_name,
                    'phone' => $userCheck->profile->phone,
                    'address' => $userCheck->profile->address,
                    'doctor_name' => $bookBefore->schedule->user->profile->full_name,
                    'date_work' => date('d-m-Y', strtotime($bookBefore->schedule->checkin)),
                    'session' => $bookBefore->schedule->session,
                    'book_time' => $bookBefore->time
                ];
                OFFICE_Email::send('mail_send_booking_patients_refuse', $userCheck->email, $paramSendMail);
                $content = 'Bệnh nhân'.$userCheck->profile->full_name.', số điện thoại:'.$userCheck->profile->phone.', địa chỉ '.$userCheck->profile->address.', đặt lịch hẹn với bác sĩ '.$bookBefore->schedule->user->profile->full_name.' vào lúc '.date('d-m-Y', strtotime($bookBefore->schedule->checkin)).''.$bookBefore->time.'-'.$bookBefore->schedule->session.' đã bị từ chối vì bác sĩ có thể bận đột xuất mmong quý bệnh nhân thông cảm! Xin vui lòng chọn khung giờ khác';
                //OFFICE_SMS::sendSMS($userCheck->profile->phone, $content);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R018", $result->id)];
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = BookBefore::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            $bookBefore_id = $id;
            $bookBefore = BookBefore::where([
                'id' => $bookBefore_id,
            ])->first();
            $userCheck = User::where(['id' => $bookBefore['user_id']])->first();
            if (!empty($userCheck) && !empty($bookBefore)) {
                $paramSendMail = [
                    'name' => $userCheck->profile->full_name,
                    'phone' => $userCheck->profile->phone,
                    'address' => $userCheck->profile->address,
                    'doctor_name' => $bookBefore->schedule->user->profile->full_name,
                    'date_work' => date('d-m-Y', strtotime($bookBefore->schedule->checkin)),
                    'session' => $bookBefore->schedule->session,
                    'book_time' => $bookBefore->time
                ];
                OFFICE_Email::send('mail_send_booking_patients_cancel', $userCheck->email, $paramSendMail);
                $content = 'Bệnh nhân'.$userCheck->profile->full_name.', số điện thoại:'.$userCheck->profile->phone.', địa chỉ '.$userCheck->profile->address.', đặt lịch hẹn với bác sĩ '.$bookBefore->schedule->user->profile->full_name.' vào lúc '.date('d-m-Y', strtotime($bookBefore->schedule->checkin)).''.$bookBefore->time.'-'.$bookBefore->schedule->session.' đã bị hủy bởi bệnh nhân! Xin vui lòng chọn khung giờ khác nếu bạn muốn khám bệnh';
                //OFFICE_SMS::sendSMS($userCheck->profile->phone, $content);
            }
            $result->delete();
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->id)];
    }

    public function countBookBeforePatient(Request $request, BookBeforeTransformer $transformer)
    {
        try {
            $timestamp = date('Y-m-d H:i:s', time());
            $before = BookBefore::model()
                ->where('book_before.created_at', '<=', $timestamp)
                ->groupBy('book_before.user_id')
                ->select('book_before.user_id as id')->get()->toArray();
            $coutPatients = count($before);
            $response = [
                'total_patients_book' => $coutPatients
            ];
            //print_r($coutPatients); die;
        } catch (\Exception $e) {
            $response = OFFICE_Error::handle($e);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['Status' => 200, 'data' => $response];
    }

    public function countBookBeforePatientToday(Request $request, BookBeforeTransformer $transformer)
    {
        try {
            $timestamp = date('Y-m-d H:i:s', time());
            $before = BookBefore::model()
                ->where('book_before.created_at', '=', $timestamp)
                ->groupBy('book_before.user_id')
                ->select('book_before.user_id as id')->get()->toArray();
            $coutPatients = count($before);
            $response = [
                'total_patients_book' => $coutPatients == 0 ? 0 : $coutPatients
            ];
            //print_r($coutPatients); die;
        } catch (\Exception $e) {
            $response = OFFICE_Error::handle($e);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['Status' => 200, 'data' => $response];
    }

    public function countBookBeforeToday(Request $request, BookBeforeTransformer $transformer)
    {
        $input = $request->all();
        try {
            $input['work_time_today'] = date('Y-m-d', time());
            $limit = array_get($input, 'limit', 20);
            $result = $this->model->searchBooksBefore($input, [], $limit);
        } catch (\Exception $e) {
            $response = OFFICE_Error::handle($e);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->paginator($result, $transformer);
    }

    public function countBookBeforeTodayofDoctors(Request $request, BookBeforeTransformer $transformer)
    {
        $input = $request->all();
        try {
            $input['work_time_today'] = date('Y-m-d', time());
            $input['doctor_user'] = OFFICE::getCurrentUserId();

            $limit = array_get($input, 'limit', 20);
            $result = $this->model->searchBooksBefore($input, [], $limit);
            Log::view($this->model->getTable());
            //count Patients have booked in today
            $coutPatients = count($result);
            $response = [
                'total_patients_book' => $coutPatients > 0 ? $coutPatients : 0
            ];
        } catch (\Exception $e) {
            $response = OFFICE_Error::handle($e);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['Status' => 200, 'Response' => $response];
    }
}