<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\Medicine;

use App\Medicine;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;
use App\Profile;
class MedicineTransformer extends TransformerAbstract
{
    public function transform(Medicine $medicine)
    {
        try {
            $created_by = (int)$medicine->created_by;
            $updated_by = (int)$medicine->updated_by;
            $profile_create_by = Profile::model()->where(['user_id' => $created_by])->first();
            $profile_update_by = Profile::model()->where(['user_id' => $updated_by])->first();
            return [
                'id'          => $medicine->id,
                'code'        => $medicine->code,
                'name'        => $medicine->name,
                'effect' => $medicine->effect,
                'medicine_origin_id'   => $medicine->medicine_origin_id,
                'medicine_origin_name'   => object_get($medicine,'medicineOrigin.name',null),
                'created_at' =>date('Y-m-d', strtotime($medicine->created_at)),
                'created_by' =>  object_get($profile_create_by, 'full_name', null),
                'updated_at' => date('Y-m-d', strtotime($medicine->updated_at)),
                'updated_by' =>  object_get($profile_update_by, 'full_name', null),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
