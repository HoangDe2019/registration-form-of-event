<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\DiseasesDiagnosed;
use App\Supports\Message;
use App\MedicalHistory;
use App\OFFICE;
use App\TestResult;
use App\User;
use App\Profile;
use App\HealthBookRecord;
use Illuminate\Support\Str;
use function Doctrine\Common\Cache\Psr6\get;

class MedicalHistoryModel extends AbstractModel
{
    public function __construct(MedicalHistory $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $history = MedicalHistory::find($id);
            if (empty($history)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }

            $checkValidPrescription = MedicalHistory::model()->where(['prescription_id' => $input['prescription_id']])->get();
            $countcheckValidPrescription = $checkValidPrescription->count();
            if ($countcheckValidPrescription > 0) {
                //If id_pre have more than 2 elements
                if ($countcheckValidPrescription >= 2) {
                    $data_preid = $input['prescription_id'];
                    throw new \Exception(Message::get("V0030", "ID: #$data_preid"));
                } else {
                    $history->user_id = OFFICE::getCurrentUserId();
                    $history->health_record_book_id = empty($input['health_record_book_id']) ? array_get($input, 'health_record_book_id', $history->health_record_book_id) : $input['health_record_book_id'];
                    $history->prescription_id = empty($input['prescription_id']) ? array_get($input, 'prescription_id', $history->prescription_id) : $input['prescription_id'];
                    $history->numbers = array_get($input, 'numbers', $history->numbers);
                    $history->checkin = !empty($input['checkin']) ? date("Y-m-d H:i:s", strtotime($input['checkin'])) : $history->checkin;
                    $history->symptom = empty($input['symptom']) ? array_get($input, 'symptom', $history->symptom) : $input['symptom'];
                    $history->follow_up = empty($input['follow_up']) ? array_get($input, 'follow_up', $history->follow_up) : $input['follow_up'];
                    $history->description = empty($input['description']) ? array_get($input, 'description', $history->description) : $input['description'];
                    $history->updated_at = date("Y-m-d H:i:s", time());
                    $history->updated_by = OFFICE::getCurrentUserId();
                    $history->save();
                }
            } else {
                $history->user_id = OFFICE::getCurrentUserId();
                $history->health_record_book_id = empty($input['health_record_book_id']) ? array_get($input, 'health_record_book_id', $history->health_record_book_id) : $input['health_record_book_id'];
                $history->prescription_id = empty($input['prescription_id']) ? array_get($input, 'prescription_id', $history->prescription_id) : $input['prescription_id'];
                $history->numbers = array_get($input, 'numbers', $history->numbers);
                $history->checkin = !empty($input['checkin']) ? date("Y-m-d H:i:s", strtotime($input['checkin'])) : $history->checkin;
                $history->symptom = empty($input['symptom']) ? array_get($input, 'symptom', $history->symptom) : $input['symptom'];
                $history->follow_up = empty($input['follow_up']) ? array_get($input, 'follow_up', $history->follow_up) : $input['follow_up'];
                $history->description = empty($input['description']) ? array_get($input, 'description', $history->description) : $input['description'];
                $history->updated_at = date("Y-m-d H:i:s", time());
                $history->updated_by = OFFICE::getCurrentUserId();
                $history->save();
            }
        } else {
            $checkSchedule_health_record = !empty($input['health_record_book_id']) ? $input['health_record_book_id'] : null;
            $checkinExits = MedicalHistory::model()->Where([
                'health_record_book_id' => $checkSchedule_health_record
            ])->max('numbers');

            // check get Number is max
            $number = 1;
            if (!empty($checkinExits)) {
                //throw new \Exception(Message::get("medical_history_error", "{$input['numbers']} của bệnh nhân có sổ khám bệnh {$input['health_record_book_id']}"));
                $param = [
                    'health_record_book_id' => $input['health_record_book_id'],
                    'user_id' => OFFICE::getCurrentUserId(),
                    'checkin' => date("Y-m-d H:i:s", time()),
                    'numbers' => $checkinExits + 1,
                    'symptom' => $input['symptom'],
                    'is_active' => 1,
                    'deleted' => 0
                ];
            } else {
                $param = [
                    'health_record_book_id' => $input['health_record_book_id'],
                    'user_id' => OFFICE::getCurrentUserId(),
                    'checkin' => date("Y-m-d H:i:s", time()),
                    'numbers' => $number,
                    'symptom' => $input['symptom'],
                    'is_active' => 1,
                    'deleted' => 0
                ];
            }
            $history = $this->create($param);

            //Them benh chuan doan khi created
//            $diagnModel = new DiseasesDiagnosed();
//            $params =[
//                'medical_history_id'=>$history->id
//            ];
//            $diag = $diagnModel->create($params);
        }
        return $history;
    }

    //CHua co thong tin benh nhan
    public function upsertv1($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $history = MedicalHistory::find($id);
            if (empty($history)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $history->user_id = OFFICE::getCurrentUserId();
            $history->health_record_book_id = array_get($input, 'health_record_book_id', $history->health_record_book_id);
            $history->prescription_id = array_get($input, 'prescription_id', $history->prescription_id);
            $history->numbers = array_get($input, 'numbers', $history->numbers);
            $history->checkin = !empty($input['checkin']) ? date("Y-m-d H:i:s", strtotime($input['checkin'])) : $history->checkin;
            $history->symptom = array_get($input, 'symptom', $history->symptom);
            $history->follow_up = array_get($input, 'follow_up', null);
            $history->description = array_get($input, 'description', NULL);
            $history->updated_at = date("Y-m-d H:i:s", time());
            $history->updated_by = OFFICE::getCurrentUserId();
            $history->save();
        } else {
            //Create User
            $paramUser = [
                'code' => array_get($input, 'phone'),
                'phone' => array_get($input, 'phone', null),
                'email' => array_get($input, 'email'),
                'username' => array_get($input, 'phone'),
                'password' => password_hash(Str::orderedUuid(), PASSWORD_BCRYPT),
                'is_active' => 1,
                'role_id' => 5,
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            $userModel = new User();
            $user = $userModel->create($paramUser);
            //Create Profile
            if (!empty($input['full_name'])) {
                $names = explode(" ", trim($input['full_name']));
                $last = $names[0];
                unset($names[0]);
                $first = !empty($names) ? implode(" ", $names) : null;
            }
            $profileModel = new Profile();
            $profileParam = [
                'user_id' => $user->id,
                'full_name' => $input['full_name'],
                'first_name' => $first,
                'address' => empty($input['address']) ? null : $input['address'],
                'avatar' => $partSaveProfile ?? null,
                'genre' => array_get($input, 'genre', 'O'),
                'birthday' => empty($input['birthday']) ? null : $input['birthday'],
                'blood_group' => array_get($input, 'blood_group', 'A+'),
                'last_name' => $last,
                'phone' => $input['phone'],
                'email' => $input['email'],
            ];
            $profileModel->create($profileParam);
            //HealthBookRecord
            $healthBookModel = new HealthBookRecord();
            $healthBookParam = [
                'user_id' => $user->id,
                'action' => date("Y-m-d H:i:s", time()),
                'is_active' => 1,
            ];
            $result = $healthBookModel->create($healthBookParam);
            //lich su kham benh
            $param = [
                // 'health_record_book_id' => $input['health_record_book_id'],
                'health_record_book_id' => $result->id,
                'user_id' => OFFICE::getCurrentUserId(),
                'checkin' => date("Y-m-d H:i:s", time()),
                'numbers' => $input['numbers'],
                'symptom' => $input['symptom'],
                'is_active' => 1,
                'deleted' => 0
            ];
            $history = $this->create($param);
            $diagnModel = new DiseasesDiagnosed();
            $params = [
                'medical_history_id' => $history->id
            ];
            $diag = $diagnModel->create($params);
        }
        return $history;
    }

    public function upsertTestResult($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $history = MedicalHistory::find($id);
            if (empty($history)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $test_result = new TestResult();
            $params = [
                'medical_history_id' => $history->id
            ];
            $diag = $test_result->create($params);
        }
        return $history;
    }

    public function fillterPatient($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['user_patients'])) {
            $query->whereHas('healthrecordbook', function ($q) use ($input) {
                $q->where('user_id', $input['user_patients']);
            });
        }
        if (!empty($input['user_doctor_id'])) {
            $query = $query->where('user_id', $input['user_doctor_id']);
        }
        //die($input['user_doctor_id']);
        $query = $query->whereNull('deleted_at')->orderBy('created_at', 'DESC');

        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }

    public function GroupByPatient($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['user_patients'])) {
            $query->whereHas('healthrecordbook', function ($q) use ($input) {
                $q->where('user_id', $input['user_patients']);
            });
        }
        if (!empty($input['user_doctor_id'])) {
            $query = $query->where('user_id', $input['user_doctor_id']);
        }
        //die($input['user_doctor_id']);
        $query = $query->whereNull('deleted_at')->orderBy('created_at', 'DESC');
        $query = $query->orderBy('health_record_book_id');
        $query->whereHas('healthrecordbook', function ($q) use ($input) {
            $q->WhereHas('user', function ($p) use ($input) {
                $p->whereHas('profile', function ($r) use ($input) {
                    $r->groupBy('avatar', 'full_name', 'user_id', 'address', 'blood_group', 'birthday', 'genre')->distinct();
                });
            });
        });
        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }

    public function searchHistoryAll($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['from_date'])) {
            $query = $query->where('checkin', '>=', $input['from_date']);
        }
        
        if (!empty($input['to_date'])) {
            $query = $query->where('checkin', '<=', $input['to_date']);
        }

        if (!empty($input['number_visit'])) {
            $query = $query->where('numbers', '=', $input['number_visit']);
        }

        $query = $query->whereNull('deleted_at')->orderBy('updated_at', 'DESC');
        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }
}
