<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\Diseases;
use App\OFFICE;

use App\Supports\Message;

class DiseasesModel extends AbstractModel
{
    public function __construct(Diseases $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $disease = Diseases::find($id);
            if (empty($disease)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $disease->name = array_get($input, 'name', null);
            $disease->code = array_get($input, 'code', null);
            $disease->department_id = array_get($input, 'department_id', null);
            $disease->symptom = array_get($input, 'symptom', $disease->phase);
            $disease->phase = array_get($input, 'phase', null);
            $disease->description = array_get($input, 'description', NULL);
            $disease->updated_at = date("Y-m-d H:i:s", time());
            $disease->updated_by = OFFICE::getCurrentUserId();
            $disease->save();
        } else {
                $param = [
                'code' => $input['code'],
                'name' => $input['name'],
                'department_id' => $input['department_id'],
                'symptom' => $input['symptom'],
                'phase' => array_get($input, 'phase'),
                'is_active' => 1,
                'description' => array_get($input, 'description')
            ];

            $disease = $this->create($param);
        }
        return $disease;
    }

    public function searchDieases($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['code'])) {
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }
        if (!empty($input['name'])) {
            $query = $query->where('name', 'like', "%{$input['name']}%");
        }
        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }
        if (isset($input['created_by'])) {
            $query = $query->where('created_by', '=', $input['created_by']);
        }
        if (!empty($input['department_name'])) {
            $query->whereHas('department', function ($q) use ($input) {
                $q->where('name', $input['department_name']);
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
