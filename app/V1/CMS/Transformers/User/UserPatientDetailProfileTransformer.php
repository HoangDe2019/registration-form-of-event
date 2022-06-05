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
use App\V1\CMS\Models\WeekModel;
use League\Fractal\TransformerAbstract;

class UserPatientDetailProfileTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        try {
            $birthday = object_get($user, 'profile.birthday', null);

            $permissionModel = new WeekModel();

            $week = [];
            $avatar = !empty($user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$user->profile->avatar) : null;

            return [
                'id' => $user->id,
                'phone' => object_get($user, "profile.phone", null),
                'email' => object_get($user, "profile.email", null),
                'role_name' => object_get($user, 'role.name', null),
                'full_name' => object_get($user, "profile.full_name", null),
                'address' => object_get($user, "profile.address", null),
                'birthday' => !empty($birthday) ? date('Y-m-d', strtotime($birthday)) : null,
                'genre_name' => config('constants.STATUS.GENRE')
                [strtoupper(object_get($user, "profile.genre", 'O'))],
                'blood_group' => object_get($user,'profile.blood_group',null),
                'avatar' => $avatar,
                'medical_record_id'=>object_get($user, "health_record_books.id", null),
                'is_active' => $user->is_active,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }

}