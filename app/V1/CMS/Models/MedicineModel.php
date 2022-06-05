<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\Medicine;
use App\OFFICE;

use App\Supports\Message;

class MedicineModel extends AbstractModel
{
    public function __construct(Medicine $model = null)
    {
        parent::__construct($model);
    }

    public function searchMedicine($input = [], $with = [], $limit = null)
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

        if (!empty($input['medicine_origin_name'])) {
            $query = $query->whereHas('medicineOrigin', function ($q) use ($input) {
                $q->where('name', 'like', "%{$input['medicine_origin_name']}%");
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

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $medicine = Medicine::find($id);
            if (empty($medicine)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $medicine->medicine_origin_id = array_get($input, 'medicine_origin_id', null);
            $medicine->code = array_get($input, 'code', null);
            $medicine->name = array_get($input, 'name', null);
            $medicine->effect = array_get($input, 'effect', null);
            $medicine->updated_at = date("Y-m-d H:i:s", time());
            $medicine->updated_by = OFFICE::getCurrentUserId();
            $medicine->save();
        } else {
            $param = [
                'medicine_origin_id' => $input['medicine_origin_id'],
                'code' => $input['code'],
                'name' => $input['name'],
                'effect' => $input['effect'],
                'is_active' => 1,
            ];
            $medicine = $this->create($param);
        }
        return $medicine;
    }
}
