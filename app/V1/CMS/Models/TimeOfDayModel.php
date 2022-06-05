<?php


namespace App\V1\CMS\Models;


use App\OFFICE;
use App\Supports\Message;
use App\TimeOfDay;

class TimeOfDayModel extends AbstractModel
{
    public function __construct(TimeOfDay $model = null)
    {
        parent::__construct($model);
    }

    public function search($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['session'])) {
            $query = $query->where('session', 'like', "%{$input['session']}%");
        }

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
            $time = TimeOfDay::find($id);
            if (empty($time)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $checkFromTime = date('H:i', strtotime($input['start_time']));
            $checkToTime = date('H:i', strtotime($input['end_time']));
            if ($checkFromTime >= $checkToTime) {
                throw new \Exception(Message::get("check_time_create", "{$input['start_time']}", "{$input['end_time']}"));
            }

            $ListTimeOfDay = TimeOfDay::model()->Where([
                'start_time' => $checkFromTime,
                'end_time' => $checkToTime,
            ])->get()->toArray();
            if (!empty($ListTimeOfDay)) {
                foreach ($ListTimeOfDay as $item) {
                    if ($checkFromTime > $item['start_time'] && $checkFromTime <= $item['end_time']) {
                        throw new \Exception(Message::get("check_time_create_of_day_validity_start", "{$checkFromTime}", "{$item['start_time']}", "{$item['end_time']}"));
                    }
                    if ($checkToTime > $item['start_time'] && $checkToTime <= $item['end_time']) {
                        throw new \Exception(Message::get("check_time_create_of_day_validity_end", "{$checkToTime}", "{$item['start_time']}", "{$item['end_time']}"));
                    }
                }
            }

            $SessionMorningFrom = date('H:i', strtotime('06:00'));
            $SessionMorningTo = date('H:i', strtotime('11:00'));
            $SessionAfternoonFrom = date('H:i', strtotime('13:30'));
            $SessionAfternoonTo = date('H:i', strtotime('16:30'));
            $itemSession = ["M", "A", "E"];

            if (($checkFromTime >= $SessionMorningFrom && $checkFromTime <= $SessionMorningTo) || ($checkToTime >= $SessionMorningFrom && $checkToTime <= $SessionMorningTo)) {
                $return = $itemSession[0];
            } else if (($checkFromTime >= $SessionAfternoonFrom && $checkFromTime <= $SessionAfternoonTo) || ($checkToTime >= $SessionAfternoonFrom && $checkToTime <= $SessionAfternoonTo)) {
                $return = $itemSession[1];
            } else {
                throw new \Exception(Message::get("check_time_MorningOrAfternoon_End", "{$checkFromTime}", "{$SessionMorningFrom}", "{$SessionMorningTo}", "{$SessionAfternoonFrom}", "{$SessionAfternoonTo}"));
            }

            $time->start_time = empty($checkFromTime) ? array_get($input, 'start_time', $time->start_time) : $checkFromTime;
            $time->end_time = empty($checkToTime) ? array_get($input, 'end_time', $time->end_time) : $checkToTime;
            $time->session = empty($return) ? array_get($input, 'session', $time->state) : $return;
            $time->updated_at = date("Y-m-d H:i:s", time());
            $time->updated_by = OFFICE::getCurrentUserId();
            $time->save();
        } else {
            $checkFromTime = date('H:i', strtotime($input['start_time']));
            $checkToTime = date('H:i', strtotime($input['end_time']));

            if ($checkFromTime >= $checkToTime) {
                throw new \Exception(Message::get("check_time_create", "{$input['start_time']}", "{$input['end_time']}"));
            }

            $ListTimeOfDay = TimeOfDay::model()->Where([
                'start_time' => $checkFromTime,
                'end_time' => $checkToTime,
            ])->get()->toArray();
            if (!empty($ListTimeOfDay)) {
                foreach ($ListTimeOfDay as $item) {
                    if ($checkFromTime > $item['start_time'] && $checkFromTime <= $item['end_time']) {
                        throw new \Exception(Message::get("check_time_create_of_day_validity_start", "{$checkFromTime}", "{$item['start_time']}", "{$item['end_time']}"));
                    }
                    if ($checkToTime > $item['start_time'] && $checkToTime <= $item['end_time']) {
                        throw new \Exception(Message::get("check_time_create_of_day_validity_end", "{$checkToTime}", "{$item['start_time']}", "{$item['end_time']}"));
                    }
                }
            }

            $SessionMorningFrom = date('H:i', strtotime('06:00'));
            $SessionMorningTo = date('H:i', strtotime('11:00'));
            $SessionAfternoonFrom = date('H:i', strtotime('13:30'));
            $SessionAfternoonTo = date('H:i', strtotime('16:30'));
            $itemSession = ["M", "A", "E"];

            if (($checkFromTime >= $SessionMorningFrom && $checkFromTime <= $SessionMorningTo) && ($checkToTime >= $SessionMorningFrom && $checkToTime <= $SessionMorningTo)) {
                $return = $itemSession[0];
            } else if (($checkFromTime >= $SessionAfternoonFrom && $checkFromTime <= $SessionAfternoonTo) && ($checkToTime >= $SessionAfternoonFrom && $checkToTime <= $SessionAfternoonTo)) {
                $return = $itemSession[1];
            } else {
                throw new \Exception(Message::get("check_time_MorningOrAfternoon_End", "{$checkFromTime}", "{$SessionMorningFrom}", "{$SessionMorningTo}", "{$SessionAfternoonFrom}", "{$SessionAfternoonTo}"));
            }
            $param = [
                'start_time' => $checkFromTime,
                'end_time' => $checkToTime,
                'session' => $return,
                'is_active' => 1
            ];
            $time = $this->create($param);

        }
        return $time;
    }
}