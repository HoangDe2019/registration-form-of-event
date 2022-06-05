<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\Department;
use App\Company;
use App\OFFICE;

use App\Supports\Message;

class DepartmentModel extends AbstractModel
{
    public function __construct(Department $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $department = Department::find($id);
            if (empty($department)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $department->name        = array_get($input, 'name', $department->name);
            $department->code        = array_get($input, 'code', $department->code);
            $department->company_id  = array_get($input, 'company_id', null);
            $department->description = array_get($input, 'description', NULL);
            $department->updated_at  = date("Y-m-d H:i:s", time());
            $department->updated_by  = OFFICE::getCurrentUserId();
            $department->save();
        } else {
            $param = [
                'code'        => $input['code'],
                'name'        => $input['name'],
                'description' => array_get($input, 'description')
            ];
            $department = $this->create($param);
        }
        return $department;
    }

    public function searchWeekofUsers($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);

        if(!empty($input['code'])){
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }

        if(!empty($input['name'])){
            $query = $query->where('name', 'like', "%{$input['name']}%");
        }

        if(!empty($input['disease_name'])){
            $query->whereHas('disease', function ($q) use ($input) {
                $q->where('name', 'like', "%{$input['disease_name']}%");
            });
        }

        $query = $query->whereNull('deleted_at')->orderBy('created_at', 'DESC');
        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }
}
