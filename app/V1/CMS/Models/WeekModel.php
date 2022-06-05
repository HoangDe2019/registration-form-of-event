<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\Supports\Message;
use App\Week;
use App\OFFICE;
use phpDocumentor\Reflection\Types\True_;
use function Aws\boolean_value;

//use App\Supports\Message;

class WeekModel extends AbstractModel
{
    public function __construct(Week $model = null)
    {
        parent::__construct($model);
    }

    public function searchWeek($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);

        $this->sortBuilder($query, $input);
        if (isset($input['checkout'])) {
            $query = $query->where('checkout', '>=', "{$input['checkout']}");
        }
		
		if(!empty($input['code'])){
            $query = $query->where('code', 'like', "%{$input['code']}%");
		}

        if(!empty($input['code'])){
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }

        if (!empty($input['user_doctor_id'])) {
            $query->whereHas('medical_schedules', function ($q) use ($input) {
                $q->where('user_id', $input['user_doctor_id']);
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

    public function searchweekSCheduled($input = [], $with = [], $limit = null){
        $query = $this->make($with);
        $this->sortBuilder($query, $input);


        if (!empty($input['user_doctor_id'])) {
            $query->whereHas('medical_schedules', function ($q) use ($input) {
                $q->where('user_id', $input['user_doctor_id']);
            });
        }
        $query = $query->whereNull('deleted_at')->orderBy('created_at', 'DESC');
        //print_r($query->paginate($limit)); die;
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

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $week = Week::find($id);
            if (empty($week)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $week->code = empty($input['code']) ? array_get($input, 'email', $week->code) : $input['code'];
            $week->checkin = empty($input['checkin']) ? array_get($input, 'checkin', $week->checkin) : $input['checkin'];
            if(array_get($input,'checkin', $week->checkin) < $input['checkout']){
                $week->checkout = empty($input['checkout'])? array_get($input, 'checkout', $week->checkout) : $input['checkout'];
            }else{
                throw new \Exception(Message::get("V018", "ID: #$id"));
            }
            $week->state = empty($input['state']) ? array_get($input, 'state', $week->state) : $input['state'];
            $week->description = empty($input['description']) ? array_get($input, 'description', NULL) : $input['description'];
            $week->updated_at = date("Y-m-d H:i:s", time());
            $week->updated_by = OFFICE::getCurrentUserId();
            $week->save();
        } else {
            $checkin = date("Y-m-d", strtotime('+ 1 day'));
            $checkout = date("Y-m-d", strtotime('+ 8 days'));
            if($checkin < $checkout) {
                $param = [
                    'code' => $input['code'],
                    'checkin' => $checkin,
                    'checkout' => $checkout,
                    'description' => array_get($input, 'description'),
                    'state' => 1,
                    'is_active' => 1
                ];
                $week = $this->create($param);
            }else{
                throw new \Exception(Message::get("V018", "time: #$checkout"));
            }
        }
        return $week;
    }
}
