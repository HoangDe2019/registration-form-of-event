<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\Medical;
use App\MedicalSchedule;
use App\OFFICE;

use App\Profile;
use App\Supports\Message;
use App\User;
use App\Week;

class MedicalScheduleModel extends AbstractModel
{
    public function __construct(MedicalSchedule $model = null)
    {
        parent::__construct($model);
    }
    public function searchUserWeekHaveSchedules($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);

        $this->sortBuilder($query, $input);
        if (isset($input['checkin'])) {
            $query = $query->where('checkin', '>=', "{$input['checkin']}");
        }
        if (!empty($input['user_doctor_id'])) {
            $query = $query->where('user_id', $input['user_doctor_id']);
        }

        if (!empty($input['doctor_name'])) {
            $query->whereHas('user', function ($q) use ($input) {
                $q->whereHas('profile', function ($p) use ($input) {
                    $p->where('full_name', 'like', "%{$input['doctor_name']}%");
                });
            });
        }

        if(!empty($input['date_Work'])){
            $dateFormat = date('Y-m-d', strtotime($input['date_Work']));
            $query = $query->where('checkin', $dateFormat);
        }

        $query = $query->whereNull('deleted_at')->orderBy('updated_at', 'DESC');
        if ($limit) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }
    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $medicalSchedule = MedicalSchedule::find($id);
            if (empty($medicalSchedule)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $medicalSchedule->user_id = array_get($input, 'user_id', $medicalSchedule->user_id);
            $checkin = !empty($input['checkin']) ? date("Y-m-d H:i:s", strtotime($input['checkin'])) : date('Y-m-d H:i:s', time());
            $week = Week::model()->where('id', $input['week_id'])->where('checkin', '<=', $checkin)->where('checkout', '>=', $checkin)->first();
            if (empty($week)) {
                throw new \Exception(Message::get("week_error", "{$input['week_id']}"));
            }
            $userdoctor = User::model()->where('id', $input['user_id'])->where('role_id', '<>', 5)->first();
            if (empty($userdoctor)){
                throw new \Exception(Message::get("user_doctor", "{$input['user_id']}"));
            }
            $medicalSchedule->week_id = array_get($input, 'week_id', $medicalSchedule->week_id);
            $medicalSchedule->user_id = array_get($input, 'user_id', $medicalSchedule->user_id);
            $medicalSchedule->checkin = !empty($input['checkin']) ? date("Y-m-d H:i:s", strtotime($input['checkin'])) : $medicalSchedule->checkin;
            $medicalSchedule->session = array_get($input, 'session', $medicalSchedule->session);
            $medicalSchedule->is_active = array_get($input, 'is_active', $medicalSchedule->is_active);
            $medicalSchedule->description = array_get($input, 'description', NULL);
            $medicalSchedule->updated_at = date("Y-m-d H:i:s", time());
            $medicalSchedule->updated_by = OFFICE::getCurrentUserId();
            $medicalSchedule->save();
        } else {
            // Check Week Create
            $checkin = !empty($input['checkin']) ? date("Y-m-d H:i:s", strtotime($input['checkin'])) : date('Y-m-d', $input['checkin']);
            $week = Week::model()->where('id', $input['week_id'])->where('checkin', '<=', $checkin)->where('checkout', '>=', $checkin)->first();
            $userdoctor = User::model()->where('id', $input['user_id'])->where('role_id', '<>', 5)->first();
            $medicalSchedule = MedicalSchedule::model()->where([
                'user_id' =>$input['user_id'],
                'week_id' =>$input['week_id'],
                'checkin'=> $input['checkin'],
                'session'=> $input['session']
            ])->first();
            if (empty($week)) {
                throw new \Exception(Message::get("week_error", "[{$input['week_id']},{$input['checkin']}]"));
            }
            if (empty($userdoctor)){
                $profile = Profile::model()->where('user_id', $input['user_id'])->first();
                throw new \Exception(Message::get("user_doctor", "{$profile['full_name']}"));
            }
            if (!empty($medicalSchedule)){
                $profile = Profile::model()->where('user_id', $input['user_id'])->first();
                throw new \Exception(Message::get("user_doctor_schedule", "{$profile['full_name']}"));
            }
            $param = [
                'user_id' => $input['user_id'],
                'week_id' => $input['week_id'],
                'session' => $input['session'],
                'checkin' => $checkin,
                'is_active' => 1,
                'description' => empty($input['description']) ? null : $input['description']
            ];
            $medicalSchedule = $this->create($param);
        }
        return $medicalSchedule;
    }
}
