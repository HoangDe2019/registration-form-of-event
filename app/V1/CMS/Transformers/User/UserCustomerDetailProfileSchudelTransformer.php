<?php
/**
 * Created by PhpStorm.
 * User: SANG NGUYEN
 * Date: 3/20/2019
 * Time: 2:41 PM
 */

namespace App\V1\CMS\Transformers\User;

use App\Supports\OFFICE_Error;
use App\User;
use App\Week;
use League\Fractal\TransformerAbstract;

class UserCustomerDetailProfileSchudelTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        try {
            $birthday = object_get($user, 'profile.birthday', null);
            $avatar = !empty($user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$user->profile->avatar) : null;
            $schedule = object_get($user, "schedule", null);
            $schedule = $schedule->load('week');
            $week = [];
            $schedule->each(function ($q) use (&$week) {
                if(empty($q['id'])){
                    $week[] = [
                        'message'=>'Not Found data'
                    ];
                }else{
                    if(date('Y-m-d', strtotime('+ 1 day')) <= $q['week']->checkin && $q['week']->checkout <= date("Y-m-d", strtotime('+ 10 days'))) {
                        $weektime = Week::model()->where(['id'=>$q['week']->id])->distinct()->first();
                        $schudel_work = object_get($weektime, 'medical_schedules', null);
                        $week[] = [
                            'week_id' => $weektime->id,
                            'start_work' => date('Y-m-d', strtotime($weektime->checkin)),
                            'end_work' => date('Y-m-d', strtotime($weektime->checkout)),
                            'date_work' => $schudel_work
                        ];
                    }else{
                        $week[] = [
                            'message'=>'Time to work from start to end are longer than is 7 days a week '
                        ];
                    }
                }
                return $week;
            });

            return [
                'id' => $user->id,
                'full_name' => object_get($user, "profile.full_name", null),
                'phone' => object_get($user, "profile.phone", null),
                'role_name' => object_get($user, 'role.name', null),
                'address' => object_get($user, "profile.address", null),
                'department' => object_get($user, "department.name", null),
                'birthday' => !empty($birthday) ? date('Y-m-d', strtotime($birthday)) : null,
                'genre_name' => config('constants.STATUS.GENRE')
                [strtoupper(object_get($user, "profile.genre", 'O'))],
                'avatar' => $avatar,
                'is_active' => $user->is_active,
                'week_work' => $week ?? collect([]),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }

}