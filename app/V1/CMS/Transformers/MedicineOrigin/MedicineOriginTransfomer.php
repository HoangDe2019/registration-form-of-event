<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\MedicineOrigin;

use App\MedicineOrigin;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class MedicineOriginTransfomer extends TransformerAbstract
{
    public function transform(MedicineOrigin $medicineOrigin)
    {
        try {
            $medicines = object_get($medicineOrigin, 'medicine', []);
            if (!empty($medicines)) {
                $medicines = $medicines->toArray();
                $medicines = array_map(function ($medicine) {
                    return [
                        'id' => $medicine['id'],
                        'name' => $medicine['name'],
                        'code' => $medicine['code'],
                        'effect' => $medicine['effect']
                    ];
                }, $medicines);
            }
            return [
                'id' => $medicineOrigin->id,
                'name' => $medicineOrigin->name,
                'medicines'=> $medicines,
                'created_at' => date('d/m/Y H:i', strtotime($medicineOrigin->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($medicineOrigin->updated_at)),
                'deleted_at' => date('d/m/Y H:i', strtotime($medicineOrigin->deleted_at))
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
