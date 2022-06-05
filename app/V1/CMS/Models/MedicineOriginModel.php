<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\Supports\Message;
use App\MedicineOrigin;
use App\OFFICE;
use phpDocumentor\Reflection\Types\True_;
use function Aws\boolean_value;

//use App\Supports\Message;

class MedicineOriginModel extends AbstractModel
{
    public function __construct(MedicineOrigin $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $MedicineOrigin = MedicineOrigin::find($id);
            if (empty($MedicineOrigin)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $MedicineOrigin->name = array_get($input, 'name', $MedicineOrigin->name);
            $MedicineOrigin->updated_at = date("Y-m-d H:i:s", time());
            $MedicineOrigin->updated_by = OFFICE::getCurrentUserId();
            $MedicineOrigin->save();
        } else {
            $param = [
                'name' => $input['name'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId()
            ];
            $MedicineOrigin = $this->create($param);
        }
        return $MedicineOrigin;
    }

    public function searchMedicineOrigin($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);

        if (!empty($input['name'])) {
            $query = $query->where('name', 'like', "%{$input['name']}%");
        }
        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }
        if (isset($input['created_by'])) {
            $query = $query->where('created_by', '=', $input['created_by']);
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
