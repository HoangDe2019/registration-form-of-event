<?php

namespace App\V1\CMS\Controllers;


use App\HealthBookRecord;
use App\MedicalHistory;

use App\Supports\Log;
use App\OFFICE;
use App\Supports\OFFICE_Error;
use App\Supports\Message;
use App\V1\CMS\Models\HealthBookRecordModel;
use App\V1\CMS\Models\MedicalHistoryModel;
use App\V1\CMS\Transformers\MedicalHistory\MedicalHistoryAllUserTransformer;
use App\V1\CMS\Transformers\MedicalHistory\MedicalHistoryPatientsTransformer;
use App\V1\CMS\Transformers\MedicalHistory\MedicalHistoryTransformer;
use App\V1\CMS\Transformers\User\UserTransformer;
use App\V1\CMS\Validators\MedicalHistoryCreateValidator;
use App\V1\CMS\Validators\MedicalHistoryTestResultUpdateValidator;
use App\V1\CMS\Validators\MedicalHistoryV1CreateValidator;
use App\V1\CMS\Validators\MedicalHistoryUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


/**
 * Class UserController
 *
 * @package App\V1\CMS\Controllers
 */
class MedicalHistoryController extends BaseController
{

    /**
     * @var MedicalHistoryModel
     */
    protected $model;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->model = new MedicalHistoryModel();
    }

    /**
     * @param UserTransformer $userTransformer
     * @return \Dingo\Api\Http\Response
     */

    //tat ca danh sach lich su kham benh
    public function search(Request $request, MedicalHistoryTransformer $transformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->searchHistoryAll($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    //Thong tin lích su kham benh cua bac si
    public function getInfo(Request $request, MedicalHistoryAllUserTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        // $user = MedicalHistory::where(['user_id' => $userId]);
        $input['user_doctor_id'] = $userId;
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->fillterPatient($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function getinfobyPatientId($id, Request $request, MedicalHistoryTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        $input['user_doctor_id'] = $userId;
        $input['user_patients'] = $id;
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->fillterPatient($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    //Nhom danh sach benh nhan da tung khac boi bac si
    public function getListPatient(Request $request, MedicalHistoryAllUserTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        $input = $request->all();
        try {
            $check = empty($input['full_name']) ? null : $input['full_name'];
            if (!empty($check)) {
                $query = MedicalHistory::model()->select([
                    'medical_histories.health_record_book_id as health_record_book_id',
                    'profiles.avatar as avatar_patient',
                    'profiles.full_name as full_name',
                    'profiles.birthday as age',
                    'profiles.address as address',
                    'profiles.phone as phone',
                    'profiles.blood_group as blood_type',
                    'profiles.genre as genre_patient',
                    'health_record_books.user_id as user_id',
                ])
                    ->join('health_record_books', 'health_record_books.id', '=', 'medical_histories.health_record_book_id')
                    ->join('users', 'users.id', '=', 'health_record_books.user_id')
                    ->join('profiles', 'profiles.user_id', '=', 'users.id')
                    ->where('profiles.full_name', 'like', "%$check%")
                    ->whereNull('health_record_books.deleted_at')
                    ->groupBy('medical_histories.health_record_book_id', 'profiles.full_name', 'profiles.avatar', 'profiles.birthday', 'profiles.address', 'profiles.phone', 'profiles.blood_group', 'profiles.genre', 'health_record_books.user_id')
                    ->get()->toArray();
                $query_data = array_map(function ($query) {
                    $avatar_patients = !empty($query['avatar_patient']) ? url('/v2') . "/img/uploads," . str_replace('/', ',', $query['avatar_patient']) : null;
                    $age = (date('Y', time()) - date('Y', strtotime($query['age'])));
                    return [
                        'health_record_book_id' => $query['health_record_book_id'],
                        'avatar_patient' => $avatar_patients,
                        'full_name' => $query['full_name'],
                        'age' => $age,
                        'address' => $query['address'],
                        'phone' => $query['phone'],
                        'blood_type' => $query['blood_type'],
                        'genre_patient' => ($query['genre_patient'] == "M") ? "Male" : "Female",
                        'user_id' => $query['user_id']
                    ];
                }, $query);

                if (empty($query_data)) {
                    $query = HealthBookRecord::model()->select([
                        'health_record_books.id as health_record_book_id',
                        'profiles.avatar as avatar_patient',
                        'profiles.full_name as full_name',
                        'profiles.birthday as age',
                        'profiles.address as address',
                        'profiles.phone as phone',
                        'profiles.blood_group as blood_type',
                        'profiles.genre as genre_patient',
                        'health_record_books.user_id as user_id',
                    ])->join('users', 'users.id', '=', 'health_record_books.user_id')
                        ->join('profiles', 'profiles.user_id', '=', 'users.id')
                        ->where('profiles.full_name', 'like', "%$check%")
                        ->whereNull('health_record_books.deleted_at')
                        ->get()->toArray();

                    //map -> array json
                    $query_data = array_map(function ($query) {
                        $avatar_patients = !empty($query['avatar_patient']) ? url('/v2') . "/img/uploads," . str_replace('/', ',', $query['avatar_patient']) : null;
                        $age = (date('Y', time()) - date('Y', strtotime($query['age'])));
                        return [
                            'health_record_book_id' => $query['health_record_book_id'],
                            'avatar_patient' => $avatar_patients,
                            'full_name' => $query['full_name'],
                            'age' => $age,
                            'address' => $query['address'],
                            'phone' => $query['phone'],
                            'blood_type' => $query['blood_type'],
                            'genre_patient' => ($query['genre_patient'] == "M") ? "Male" : "Female",
                            'user_id' => $query['user_id']
                        ];
                    }, $query);
                }
            } else {
                $query = MedicalHistory::model()->select([
                    'medical_histories.health_record_book_id as health_record_book_id',
                    'profiles.avatar as avatar_patient',
                    'profiles.full_name as full_name',
                    'profiles.birthday as age',
                    'profiles.address as address',
                    'profiles.phone as phone',
                    'profiles.blood_group as blood_type',
                    'profiles.genre as genre_patient',
                    'health_record_books.user_id as user_id',
                ])
                    ->join('health_record_books', 'health_record_books.id', '=', 'medical_histories.health_record_book_id')
                    ->join('users', 'users.id', '=', 'health_record_books.user_id')
                    ->join('profiles', 'profiles.user_id', '=', 'users.id')
                    ->where([
                        'medical_histories.user_id' => $userId
                    ])
                    ->whereNull('health_record_books.deleted_at')
                    ->groupBy('medical_histories.health_record_book_id', 'profiles.full_name', 'profiles.avatar', 'profiles.birthday', 'profiles.address', 'profiles.phone', 'profiles.blood_group', 'profiles.genre', 'health_record_books.user_id')
                    ->get()->toArray();

                $query_data = array_map(function ($query) {
                    $avatar_patients = !empty($query['avatar_patient']) ? url('/v2') . "/img/uploads," . str_replace('/', ',', $query['avatar_patient']) : null;
                    $age = (date('Y', time()) - date('Y', strtotime($query['age'])));
                    return [
                        'health_record_book_id' => $query['health_record_book_id'],
                        'avatar_patient' => $avatar_patients,
                        'full_name' => $query['full_name'],
                        'age' => $age,
                        'address' => $query['address'],
                        'phone' => $query['phone'],
                        'blood_type' => $query['blood_type'],
                        'genre_patient' => ($query['genre_patient'] == "M") ? "Male" : "Female",
                        'user_id' => $query['user_id']
                    ];
                }, $query);
                if (empty($query_data)) {
                    return ['status' => Response::HTTP_NO_CONTENT, "data" => ['Không tìm thấy thông tin này!']];
                }
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return ['status' => Response::HTTP_OK, 'data' => $query_data];
    }

    public function getidPatientHistoryId($id, Request $request, MedicalHistoryTransformer $transformer)
    {
        $input['user_patients'] = $id;
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->fillterPatient($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    //Lay thong tin lich su cua benh nhan theo id
    public function getidPatientHistory($id, Request $request, MedicalHistoryTransformer $transformer)
    {
        $input = $request->all();
        $input['user_patients'] = $id;
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->fillterPatient($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function getinfobyPatient(Request $request, MedicalHistoryPatientsTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        $input['user_patients'] = $userId;
        $limit = array_get($input, 'limit', 20);
        $result = $this->model->fillterPatient($input, [], $limit);
        return $this->response->paginator($result, $transformer);
    }

    public function detail($id, Request $request, MedicalHistoryTransformer $userPatient)
    {
        $userId = OFFICE::getCurrentUserId();
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

    public function create(Request $request, MedicalHistoryCreateValidator $createValidator, MedicalHistoryTransformer $transformer)
    {
        $input = $request->all();
        $createValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::create($this->model->getTable(), $result->symptom);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R001", $result->id)];
    }

    public function createV1(Request $request, MedicalHistoryV1CreateValidator $createValidator, MedicalHistoryTransformer $transformer)
    {
        $input = $request->all();
        $createValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsertv1($input);
            Log::create($this->model->getTable(), $result->symptom);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R001", $result->id)];
    }

    public function update($id, Request $request, MedicalHistoryUpdateValidator $updateValidator, MedicalHistoryTransformer $transformer)
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
        return ['status' => Message::get("R002", $result->id)];
    }

    public function creatTestResult($id, Request $request, MedicalHistoryTestResultUpdateValidator $updateValidator, MedicalHistoryTransformer $transformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $updateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsertTestResult($input);
            Log::update($this->model->getTable());
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R002", $result->id)];
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = MedicalHistory::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            $result->delete();
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("R003", $result->id)];
    }

    public function CountPatientToday(Request $request, MedicalHistoryAllUserTransformer $transformer)
    {
        $userId = OFFICE::getCurrentUserId();
        $input = $request->all();
        try {
            $query = MedicalHistory::model()->select([
                'medical_histories.health_record_book_id as health_record_book_id',
                'profiles.avatar as avatar_patient',
                'profiles.full_name as full_name',
                'profiles.birthday as age',
                'profiles.address as address',
                'profiles.phone as phone',
                'profiles.blood_group as blood_type',
                'profiles.genre as genre_patient',
                'health_record_books.user_id as user_id',
            ])
                ->join('health_record_books', 'health_record_books.id', '=', 'medical_histories.health_record_book_id')
                ->join('users', 'users.id', '=', 'health_record_books.user_id')
                ->join('profiles', 'profiles.user_id', '=', 'users.id')
                ->where([
                    'medical_histories.user_id' => $userId,
                    'checkin' => time()
                ])
                ->whereNull('health_record_books.deleted_at')
                ->groupBy('medical_histories.health_record_book_id', 'profiles.full_name', 'profiles.avatar', 'profiles.birthday', 'profiles.address', 'profiles.phone', 'profiles.blood_group', 'profiles.genre', 'health_record_books.user_id')
                ->get()->toArray();
            $coutPatients = count($query);
            $response = [
                'total_patients_book' => $coutPatients > 0 ? $coutPatients : 0
            ];
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return ['status' => Response::HTTP_OK, 'Response' => $response];
    }
}