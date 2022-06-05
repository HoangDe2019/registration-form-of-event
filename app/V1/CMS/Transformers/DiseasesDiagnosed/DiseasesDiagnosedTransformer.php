<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App\V1\CMS\Transformers\DiseasesDiagnosed;

use App\Department;
use App\Diseases;
use App\DiseasesDiagnosed;
use App\MedicalHistory;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\DiseasesDiagnosedModel;
use App\V1\CMS\Models\DiseasesModel;
use League\Fractal\TransformerAbstract;

class DiseasesDiagnosedTransformer extends TransformerAbstract
{
    public function transform(MedicalHistory $diagnosed)
    {
        try {

            $disease = new DiseasesModel();
            $diseases_diagnosed = new DiseasesDiagnosedModel();
            $pre = DiseasesDiagnosed::model()
                ->select([
                    $disease->getTable() . '.code as diseases_code',
                    $disease->getTable() . '.name as diseases_name',
                    $diseases_diagnosed->getTable() . '.state as state',
                ])
                ->where('medical_history_id', $diagnosed->id)
                ->whereNull($disease->getTable() . '.deleted_at')
                ->join($disease->getTable(), $disease->getTable() . '.id', '=',
                    $diseases_diagnosed->getTable() . '.diseases_id')
                ->get()->toArray();
            $predetails1 = array_pluck($pre, "diseases_code");
            $predetails2 = array_pluck($pre, "diseases_name");
            $predetails3 = array_pluck($pre, "state");
            $code=array($predetails1);
            $name = array($predetails2);
            $state = array($predetails3);

            $diseases_diagnosed = object_get($diagnosed, 'diseases_diagnosed', []);
            if (!empty($diseases_diagnosed)) {
                $diseases_diagnosed = $diseases_diagnosed->toArray();
                $diseases_diagnosed = array_map(function ($diseases_diagnosed) {
                    $Diseases = Diseases::model()->find($diseases_diagnosed['diseases_id']);
                    $department = Department::model()->find(object_get($Diseases,'department_id', null));
                   // print_r($department); die;
                    return [
                        'diseases_id' => object_get($Diseases,'id', null),
                        'name' => object_get($Diseases,'name', null),
                        'code' => object_get($Diseases,'code', null),
                        'department_name' => object_get($department,'name', null),
                    ];
                }, $diseases_diagnosed);
            }

            return [
                'medical_history_id' => $diagnosed->id,
                'state'=>$diseases_diagnosed
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
