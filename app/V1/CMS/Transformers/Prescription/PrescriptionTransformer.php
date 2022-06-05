<?php
/**
 * User: Administrator
 * Date: 12/10/2018
 * Time: 07:25 PM
 */

namespace App\V1\CMS\Transformers\Prescription;



use App\Prescription;
use App\PrescriptionDetail;
use App\RolePermission;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\MedicineModel;
use App\V1\CMS\Models\PrescriptionDetailModel;
use App\V1\CMS\Models\RolePermissionModel;
use League\Fractal\TransformerAbstract;

class PrescriptionTransformer extends TransformerAbstract
{
    public function transform(Prescription $prescription)
    {
        try {
            $medicine = new MedicineModel();
            $predetail = new PrescriptionDetailModel();
            $pre = PrescriptionDetail::model()
                ->select([
                    $medicine->getTable() . '.id as id',
                    $medicine->getTable() . '.code as medicine_code',
                    $medicine->getTable() . '.name as medicine_name',
                    $predetail->getTable() . '.unit as medicine_unit',
                    $predetail->getTable() . '.numbers as medicine_numbers',
                    $predetail->getTable() . '.use as medicine_use'
                ])
                ->where('prescription_id', $prescription->id)
                ->whereNull($medicine->getTable() . '.deleted_at')
                ->join($medicine->getTable(), $medicine->getTable() . '.id', '=',
                    $predetail->getTable() . '.medicine_id')
                ->get()->toArray();
//
            $predetails0= array_pluck($pre, "id");
            $predetails1 = array_pluck($pre, "medicine_code");
            $predetails2 = array_pluck($pre, "medicine_name");
            $predetails3 = array_pluck($pre, "medicine_unit");
            $predetails4 = array_pluck($pre, "medicine_numbers");
            $predetails5 = array_pluck($pre, "medicine_use");

//            $medicine_id=array_sort($predetails0);
//            $medicine_code=array_sort($predetails1);
//            $medicine_name=array_sort($predetails2);
//            $medicine_unit=array_sort($predetails3);
//            $medicine_numbers=array_sort($predetails4);
//            $medicine_use=array_sort($predetails5);
            return [
                'id' => $prescription->id,
                'name' => $prescription->action,
                'code' => $prescription->code,
                'medicine_id' => $predetails0,
                'medicine_code' => $predetails1,
                'medicine_name' =>$predetails2 ,
                'medicine_unit' => $predetails3,
                'medicine_numbers' =>$predetails4,
                'medicine_use' => $predetails5,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
