<?php


namespace App\V1\CMS\Transformers\Department;
use App\Department;
use App\Profile;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class DiseasesOfDepartmentsTransformer extends TransformerAbstract
{
    public function transform(Department $departments)
    {
        try {
            $created_by = Profile::model()->where('user_id', '=', $departments['created_by'])->first();
            $updated_by = Profile::model()->where('user_id', '=', $departments['updated_by'])->first();
            // print_r($full_name->full_name); die;
            $diseases = object_get($departments, 'disease', null);
            if(!empty($diseases)){
                $diseases = $diseases->toArray();
                $diseases = array_map(function ($diseases) {
                    $created_by = Profile::model()->where('user_id', '=', $diseases['created_by'])->first();
                    $updated_by = Profile::model()->where('user_id', '=', $diseases['updated_by'])->first();

                    return [
                        'disease_id' =>$diseases['id'],
                        'disease_code' => $diseases['code'],
                        'disease_name' => $diseases['name'],
                        'disease_symptom'=>$diseases['symptom'],
                        'diseases_phase'=>$diseases['phase'],
                        'created_at'=> date('Y-m-d', strtotime($diseases['created_at'])),
                        'created_by'=> $created_by->full_name,
                        'updated_at'=> date('Y-m-d', strtotime($diseases['updated_at'])),
                        'updated_by'=> $updated_by->full_name
                    ];
                }, $diseases);
            }

            return [
                'id'          => $departments->id,
                'code'        => $departments->code,
                'name'        => $departments->name,
                'description' => $departments->description,
                'created_at'=> date('Y-m-d', strtotime($departments['created_at'])),
                'created_by'=> $created_by->full_name,
                'updated_at'=> date('Y-m-d', strtotime($departments['updated_at'])),
                'updated_by'=> $updated_by->full_name,
                'diseases'    => $diseases
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}