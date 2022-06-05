<?php
/**
 * Created by PhpStorm.
 * User: SANG NGUYEN
 * Date: 3/20/2019
 * Time: 2:41 PM
 */

namespace App\V1\CMS\Transformers\User;

use App\MedicalSchedule;
use App\Supports\OFFICE_Error;
use App\User;
use App\V1\CMS\Models\WeekModel;
use App\Week;
use League\Fractal\TransformerAbstract;

class UserCustomerDetailProfileTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        try {
            $birthday = object_get($user, 'profile.birthday', null);
            $permissionModel = new WeekModel();
            $week = [];
            $avatar = !empty($user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$user->profile->avatar) : null;
            $schedule = object_get($user, "schedule", null);
            $schedule = $schedule->load('week');
            $schedule->each(function ($q) use (&$week) {

                $week[] = [
                    'week_id' => $q['week']->id,
                    'start_work' => date('Y-m-d', strtotime($q['week']->checkin)),
                    'end_work' => date('Y-m-d', strtotime($q['week']->checkout)),
                ];
                return $week;
            });
            $schedules1 = object_get($user, 'schedule', []);
            if (!empty($schedules1)) {
                $schedules1 = $schedules1->toArray();
                $schedules1 = array_map(function ($p) {
                    return [
                        'medical_history_id'=>$p['id'],
                        'session' => $p['session'],
                        'date_work'=> date('Y-m-d', strtotime($p['checkin'])),
                    ];
                }, $schedules1);
            }

            $educationArray = object_get($user, 'education', []);
            if (!empty($educationArray)){
                $educationArray = $educationArray->toArray();
                $educationArray = array_map(function ($p){
                    return [
                        'degree' => $p['degree'],
                        'college_or_universiy'=> $p['college_or_universiy'],
                        'year_of_completion'=>!empty($p['year_of_completion']) ? date("Y", strtotime($p['year_of_completion'])): null
                    ];
                },$educationArray);
            }

            return [
                'id' => $user->id,
                'phone' => object_get($user, "profile.phone", null),
                'email' => object_get($user, "profile.email", null),
                'role_name' => object_get($user, 'role.name', null),
                'full_name' => object_get($user, "profile.full_name", null),
                'address' => object_get($user, "profile.address", null),
                'department' => object_get($user, "department.name", null),
                'branch_name' => object_get($user, "profile.branch_name", null),
                'birthday' => !empty($birthday) ? date('Y-m-d', strtotime($birthday)) : null,
                'genre_name' => config('constants.STATUS.GENRE')
                [strtoupper(object_get($user, "profile.genre", 'O'))],
                'avatar' => $avatar,
                'id_number' => object_get($user, "profile.id_number", null),
                'is_active' => $user->is_active,
                'time_active' => $week ?? collect([]),
                'sessions'=> $schedules1,
                'education' => $educationArray
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }

}