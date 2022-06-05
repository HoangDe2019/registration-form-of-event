<?php

/**
 * User: Administrator
 * Date: 12/10/2018
 * Time: 06:33 PM
 */

namespace App\V1\CMS\Models;


use App\MedicalSchedule;
use App\OFFICE;
use App\BookBefore;
use App\Role;
use App\Supports\Message;
use App\TimeOfDay;
use App\User;
use Illuminate\Support\Facades\DB;

class BookBeforeModel extends AbstractModel
{
    public function __construct(BookBefore $model = null)
    {
        parent::__construct($model);
    }

    /**
     * @param array $input
     * @param array $with
     * @param null $limit
     * @return mixed
     */

    /**
     * @param $input
     * @return mixed
     * @throws \Exception
     */
    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $bookbefore = BookBefore::find($id);
            if (empty($bookbefore)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $bookbefore->medical_schedule_id = empty($input['medical_schedule_id']) ? array_get($input, 'medical_schedule_id', $bookbefore->medical_schedule_id) : $input['medical_schedule_id'];
            $bookbefore->user_id = empty($input['user_id']) ? array_get($input, 'user_id', $bookbefore->user_id) : $input['user_id'];
            $bookbefore->name = empty($input['name']) ? array_get($input, 'name', $bookbefore->name) : $input['name'];
            //$bookbefore->time = empty($input['time']) ? array_get($input, 'time', $bookbefore->time) : $input['time'];
            $bookbefore->state = empty($input['state']) ? array_get($input, 'state', $bookbefore->state) : $input['state'];
            $bookbefore->description = empty($input['description']) ? array_get($input, 'description', $bookbefore->description) : $input['description'];
            $bookbefore->is_active = empty($input['is_active']) ? array_get($input, 'is_active', $bookbefore->is_active) : $input['is_active'];
            $bookbefore->updated_at = date("Y-m-d H:i:s", time());
            $bookbefore->updated_by = OFFICE::getCurrentUserId();
            $bookbefore->save();
        } else {
            $checkSchedule = !empty($input['medical_schedule_id']) ? $input['medical_schedule_id'] : null;
            //$checkTime = !empty($input['time']) ? $input['time'] : null;

            //getInfo Time Of WOrk
            $time_of_work = !empty($input['options_time']) ? $input['options_time'] : null;
            $checkDataTimeOfWOrk = TimeOfDay::model()->where(['id'=>$time_of_work])->first();
            if (empty($checkDataTimeOfWOrk)) {
                throw new \Exception(Message::get("V003", "ID #$time_of_work"));
            }

            //get Hour * 60 + minutes
            $from_timeInput = (date('H', strtotime($checkDataTimeOfWOrk['start_time'])) * 60) + date('i', strtotime($checkDataTimeOfWOrk['start_time']));
            $to_timeInput = (date('H', strtotime($checkDataTimeOfWOrk['end_time'])) * 60) + date('i', strtotime($checkDataTimeOfWOrk['end_time']));
            //print_r($from_timeInput); die;

            $validateBooking = BookBefore::model()->Where([
                'medical_schedule_id' => $checkSchedule,
                'from_book' => $from_timeInput,
                'to_book' => $to_timeInput,
                'user_id' => OFFICE::getCurrentUserId(),
                'state' => 0,
                'deleted' => 0
            ])->first();

            $validateBookingStatus = BookBefore::model()->Where([
                'medical_schedule_id' => $checkSchedule,
                'from_book' => $from_timeInput,
                'to_book' => $to_timeInput,
                'user_id' => OFFICE::getCurrentUserId(),
                'state' => 1,
                'deleted' => 0
            ])->first();

            //Dem so luot dat cua benh nhan cho 1 khung gio trong ngay hom nay
            $countBook = BookBefore::model()->Where([
                'medical_schedule_id' => $checkSchedule,
                'from_book' => $from_timeInput,
                'to_book' => $to_timeInput,
                'state' => 0,
                'deleted' => 0
            ])->get();
            $totalItems = $countBook->count();

            $countBookStatus = BookBefore::model()->Where([
                'medical_schedule_id' => $checkSchedule,
                'from_book' => $from_timeInput,
                'to_book' => $to_timeInput,
                'state' => 1,
                'deleted' => 0
            ])->get();
            $totalItemsStatus = $countBookStatus->count();

            // xem trang thai 0 hoac 1 dem so luot chon toi da chi co 5
            if ($totalItems >= 6 || $totalItemsStatus >= 6) {
                throw new \Exception(Message::get("booking_number_count", "{$checkDataTimeOfWOrk['start_time']}", "{$checkDataTimeOfWOrk['end_time']}"));
            }

            //Nếu trạng thái 0 hoặc 1 thì sẽ không được thêm vào
            if (!empty($validateBooking) || !empty($validateBookingStatus)) {
                throw new \Exception(Message::get("booking_time", "{$checkDataTimeOfWOrk['start_time']}", "{$checkDataTimeOfWOrk['end_time']}"));
            }

            if ($from_timeInput > $to_timeInput) {
                throw new \Exception(Message::get("book_check", "{$checkDataTimeOfWOrk['start_time']}", "{$checkDataTimeOfWOrk['end_time']}"));
            }

            //status 0
            $BookBeforeCheckTimeStatus0 = BookBefore::model()->Where([
                'user_id' => OFFICE::getCurrentUserId(),
                'medical_schedule_id' => $checkSchedule,
                'state' => 0
            ])->get()->toArray();


            foreach ($BookBeforeCheckTimeStatus0 as $item) {
                if (($from_timeInput > $item['from_book'] && $from_timeInput < $item['to_book']) || ($to_timeInput > $item['from_book'] && $to_timeInput < $item['to_book'])) {
                    $from_book = $this->ConvertIntoTime($item['from_book']);
                    $to_book = $this->ConvertIntoTime($item['to_book']);
                    throw new \Exception(Message::get("book_checked_time_choice", "{$checkDataTimeOfWOrk['start_time']}", "{$checkDataTimeOfWOrk['end_time']}", "{$from_book}", "{$to_book}"));
                }
            }

            //check sáng hay chiều
            $sesionCheckBook = MedicalSchedule::model()->find($checkSchedule);
            $checkTimeWorkSetup = $checkDataTimeOfWOrk['session'] == "M" ? "Sáng" : "Chiều";
            if($sesionCheckBook['session'] != $checkTimeWorkSetup){
                throw new \Exception(Message::get("booking_check_sessions", "{$checkDataTimeOfWOrk['start_time']}", "{$checkDataTimeOfWOrk['end_time']}", "{$sesionCheckBook['session']}"));
            }

            //statetus 1
            $BookBeforeCheckTimeStatus1 = BookBefore::model()->Where([
                'user_id' => OFFICE::getCurrentUserId(),
                'medical_schedule_id' => $checkSchedule,
                'state' => 1
            ])->get()->toArray();

            foreach ($BookBeforeCheckTimeStatus1 as $itemList) {
                if (($from_timeInput > $itemList['from_book'] && $from_timeInput < $itemList['to_book']) || ($to_timeInput > $itemList['from_book'] && $to_timeInput < $itemList['to_book'])) {
                    $from_book = $this->ConvertIntoTime($itemList['from_book']);
                    $to_book = $this->ConvertIntoTime($itemList['to_book']);
                    throw new \Exception(Message::get("book_checked_time_choice", "{$checkDataTimeOfWOrk['start_time']}", "{$checkDataTimeOfWOrk['end_time']}", "{$from_book}", "{$to_book}"));
                }
            }

            $timeTemp = $checkDataTimeOfWOrk['start_time'] . " - " . $checkDataTimeOfWOrk['end_time'];


            $userLoginCheck = User::model()->where([
                'id'=>OFFICE::getCurrentUserId()
            ])->first();

            $roleName = Role::model()->where(['id'=>$userLoginCheck['role_id']])->first();

            if($roleName['id'] != 5){
                throw new \Exception(Message::get("booking_check_userLogin_patient", "{$roleName['name']}"));
            }
            //print_r($timeTemp); die;

            $param = [
                'medical_schedule_id' => $input['medical_schedule_id'],
                'user_id' => $userLoginCheck['id'],
                'times_of_day_id' => $time_of_work,
                'name' => $input['name'],
                'time' => $timeTemp,
                'from_book' => $from_timeInput,
                'to_book' => $to_timeInput,
                'state' => array_get($input, 'state', 0),
                'is_active' => 1,
                'deleted' => 0,
                'description' => array_get($input, 'description'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
                'updated_at' => date("Y-m-d H:i:s", time()),
                'updated_by' => OFFICE::getCurrentUserId()
            ];
            $bookbefore = $this->create($param);
        }
        return $bookbefore;
    }

    public function ConvertIntoTime($timeDB)
    {
        if ($timeDB >= 0 && $timeDB < 60) {
            $minutes = $timeDB - 0;
            $toDateString = strval("00:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 60 && $timeDB < 120) {
            $minutes = $timeDB - 60;
            $toDateString = strval("01:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 120 && $timeDB < 180) {
            $minutes = $timeDB - 120;
            $toDateString = strval("02:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 180 && $timeDB < 240) {
            $minutes = $timeDB - 180;
            $toDateString = strval("03:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 240 && $timeDB < 300) {
            $minutes = $timeDB - 240;
            $toDateString = strval("04:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 300 && $timeDB < 360) {
            $minutes = $timeDB - 300;
            $toDateString = strval("05:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 360 && $timeDB < 420) {
            $minutes = $timeDB - 360;
            $toDateString = strval("06:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 420 && $timeDB < 480) {
            $minutes = $timeDB - 420;
            $toDateString = strval("07:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 480 && $timeDB < 540) {
            $minutes = $timeDB - 480;
            $toDateString = strval("08:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 540 && $timeDB < 600) {
            $minutes = $timeDB - 540;
            $toDateString = strval("09:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 600 && $timeDB < 660) {
            $minutes = $timeDB - 600;
            $toDateString = strval("10:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 660 && $timeDB < 720) {
            $minutes = $timeDB - 660;
            $toDateString = strval("11:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 720 && $timeDB < 780) {
            $minutes = $timeDB - 720;
            $toDateString = strval("12:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 780 && $timeDB < 840) {
            $minutes = $timeDB - 780;
            $toDateString = strval("13:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 840 && $timeDB < 900) {
            $minutes = $timeDB - 840;
            $toDateString = strval("14:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 900 && $timeDB < 960) {
            $minutes = $timeDB - 900;
            $toDateString = strval("15:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 960 && $timeDB < 1020) {
            $minutes = $timeDB - 960;
            $toDateString = strval("16:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 1020 && $timeDB < 1080) {
            $minutes = $timeDB - 1020;
            $toDateString = strval("17:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 1080 && $timeDB < 1140) {
            $minutes = $timeDB - 1080;
            $toDateString = strval("18:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 1140 && $timeDB < 1200) {
            $minutes = $timeDB - 1140;
            $toDateString = strval("19:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 1200 && $timeDB < 1260) {
            $minutes = $timeDB - 1200;
            $toDateString = strval("20:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 1260 && $timeDB < 1320) {
            $minutes = $timeDB - 1260;
            $toDateString = strval("21:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else if ($timeDB >= 1320 && $timeDB < 1380) {
            $minutes = $timeDB - 1320;
            $toDateString = strval("22:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        } else {
            $minutes = $timeDB - 1380;
            $toDateString = strval("23:{$minutes}");
            $ResultCovert = date('H:i', strtotime($toDateString));
        }
        return $ResultCovert;
    }

    public function fillter($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['code'])) {
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }
        if (!empty($input['phone'])) {
            $query = $query->where('phone', 'like', "%{$input['phone']}%");
        }
        if (!empty($input['user_patients'])) {
            $query = $query->where('user_id', $input['user_patients']);
        }
        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }

        if (!empty($input['state'])) {
            $query = $query->where('state', $input['state']);
        }

        if (!empty($input['role_code'])) {
            $query->whereHas('role', function ($q) use ($input) {
                $q->where('code', $input['role_code']);
            });
        }
        if (!empty($input['genre'])) {
            $query->whereHas('profile', function ($q) use ($input) {
                $q->where('genre', $input['genre']);
            });
        }
        $idBook = new BookBeforeModel();
        $orderBy = $idBook->getTable();
        if (!empty($input['user_doctor'])) {
            $query->whereHas('schedule', function ($q) use ($input) {
                $q->where('user_id', $input['user_doctor']);
            });
        }
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

    public function searchBooksBefore($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['time-option'])) {
            $query = $query->where('time', $input['time-option']);
        }
        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }
        if (!empty($input['genre_patient'])) {
            $query->whereHas('user', function ($q) use ($input) {
                $q->whereHas('profile', function ($qp) use ($input) {
                    $qp->where('genre', $input['genre_patient']);
                });
            });
        }
        if (!empty($input['name_patient'])) {
            $query->whereHas('user', function ($q) use ($input) {
                $q->whereHas('profile', function ($qp) use ($input) {
                    $qp->where('full_name', 'like', "%{$input['name_patient']}%");
                });
            });
        }
        if (!empty($input['phone_number'])) {
            $query->whereHas('user', function ($q) use ($input) {
                $q->whereHas('profile', function ($qp) use ($input) {
                    $qp->where('phone', 'like', "%{$input['phone_number']}%");
                });
            });
        }
        $idBook = new BookBeforeModel();
        $orderBy = $idBook->getTable();
        if (!empty($input['work_time_today'])) {
            $query->whereHas('schedule', function ($q) use ($input) {
                $q->where('checkin', $input['work_time_today']);
            });
        }

        if (!empty($input['doctor_user'])) {
            $query->whereHas('schedule', function ($q) use ($input) {
                $q->where('user_id', $input['doctor_user']);
            });
        }

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
}