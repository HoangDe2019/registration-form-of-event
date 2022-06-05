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
use League\Fractal\TransformerAbstract;

class UserCustomerProfileTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        try {
            $birthday = object_get($user, 'profile.birthday', null);
            $schedules1 = object_get($user, 'schedule', []);
            if (!empty($schedules1)) {
                $schedules1 = $schedules1->toArray();
                $schedules1 = array_map(function ($p) {
                    return [
                        'session' => $p['session'],
                        'date_work'=> $p['checkin'],
                    ];
                }, $schedules1);
            }
            $avatar = !empty($user->profile->avatar) ? url('/v2') . "/img/uploads," . str_replace('/', ',',$user->profile->avatar) : null;
            return [
                'id' => $user->id,
                'username'=> $user->username,
                'role_id' => $user->role_id,
                'supper_admin'=> $user->is_super,
                'email'=> $user->email,
                'phone'=> $user->phone,
                'role_name'=>object_get($user,'role.name', null),
                'full_name' => object_get($user, "profile.full_name", null),
                'address' => object_get($user, "profile.address", null),
                'department_code' => object_get($user, "department.code", null),
                'department_name' => object_get($user, "department.name", null),
                'genre_name' => config('constants.STATUS.GENRE')
                [strtoupper(object_get($user, "profile.genre", 'O'))],
                'avatar' => $avatar,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }

}