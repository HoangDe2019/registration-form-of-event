<?php
/**
 * User: Administrator
 * Date: 12/10/2018
 * Time: 07:25 PM
 */

namespace App\V1\CMS\Transformers\HealthBookRecord;


use App\HealthBookRecord;
use App\MedicalSchedule;
use App\Role;
use App\RolePermission;
use App\Supports\OFFICE_Error;
use App\User;
use App\V1\CMS\Models\HealthBookRecordModel;
use App\V1\CMS\Models\UserModel;
use League\Fractal\TransformerAbstract;

class HealthBookRecordTransformer extends TransformerAbstract
{
    public function transform(HealthBookRecord $bookRecord)
    {

        try {

            return [
                'id' => $bookRecord->id,
                'full_name' => object_get($bookRecord,'user.profile.full_name', null),
                'role_name' => object_get($bookRecord,'user.role.name', null),
                'phone_numbers' => object_get($bookRecord, 'user.profile.phone'),
                'email'=> object_get($bookRecord, 'user.email', null),
                'address' => object_get($bookRecord,'user.profile.address', null),
                'date_birth'=>date('d-m-Y', strtotime(object_get($bookRecord, 'user.profile.birthday', null))),
                'created_date' => date('d-m-Y', strtotime($bookRecord->action)),
                'username' => object_get($bookRecord, 'user.username'),
                'active_date' => date('d-m-Y', strtotime($bookRecord->active_at))
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['loi']);
        }
    }
}
