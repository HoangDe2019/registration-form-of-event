<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 3/27/2019
 * Time: 9:20 PM
 */

namespace App\V1\CMS\Transformers\Prescription;

use App\Prescription;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

/**
 * Class PermissionTransformer
 * @package App\V1\CMS\Transformers\Permission
 */
class PrescriptionDrTransformer extends TransformerAbstract
{
    /**
     * @param Prescription $permission
     * @return array
     * @throws \Exception
     */
    public function transform(Prescription $prescription)
    {
        try {
            $avatar = !empty($prescription->medicalhistory->user->profile->avatar) ? url('/v2') . "/img/uploads," .str_replace('/',',',$prescription->medicalhistory->user->profile->avatar) : null;

            return [
                'id' => $prescription->id,
                'code' => $prescription->code,
                'name' => $prescription->action,
                'is_active' => $prescription->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($prescription->created_at)),
                'updated_at' => !empty($prescription->updated_at) ? date('d/m/Y H:i', strtotime($prescription->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}