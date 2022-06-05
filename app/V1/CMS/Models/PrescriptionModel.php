<?php

/**
 * User: Administrator
 * Date: 28/09/2018
 * Time: 09:32 PM
 */

namespace App\V1\CMS\Models;

use App\Company;
use App\HealthBookRecord;
use App\MedicalHistory;
use App\Prescription;
use App\PrescriptionDetail;
use App\UserHealthBookRecord;
use App\Profile;
use App\Supports\Message;
use App\User;
use Illuminate\Support\Facades\DB;
use App\OFFICE;
use Illuminate\Support\Str;


class PrescriptionModel extends AbstractModel
{
    public function __construct(Prescription $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $pres = Prescription::find($id);
            if (empty($pres)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $pres->action =  empty($input['action']) ? array_get($input, 'action', $pres->action): $input['action'];
            $pres->code = empty($input['code']) ? array_get($input, 'code', $pres->code):$input['code'];
            $pres->is_active = array_get($input, 'is_active', $pres->is_active);
            $pres->updated_at = date("Y-m-d H:i:s", time());
            $pres->updated_by = OFFICE::getCurrentUserId();
            $pres->save();
        } else {
            $param = [
                'action' => array_get($input, 'action'),
                'code' => Str::orderedUuid(),
                'is_active' => 1,
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => OFFICE::getCurrentUserId(),
            ];
            $pres = $this->create($param);
        }
        //Finish Create Prescription
        // Create or Update PrescriptionDetail
        $detail = PrescriptionDetail::model()->where('prescription_id', $pres->id)->first();
        return $pres;
    }

    public function searchPres($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        if (!empty($input['code'])) {
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }
        if (!empty($input['name'])) {
            $query = $query->where('action', 'like', "%{$input['name']}%");
        }

        if (isset($input['is_active'])) {
            $query = $query->where('is_active', 'like', "%{$input['is_active']}%");
        }

        if (isset($input['created_by'])) {
            $query = $query->where('created_by', '=', $input['created_by']);
        }
        //Loc du lieu cho benh nhan
        if (!empty($input['user_patients'])) {
            $query->whereHas('medicalhistory', function ($q) use ($input) {
                $q->whereHas('healthrecordbook', function ($qp) use ($input) {
                   $qp->where('user_id','=', $input['user_patients']);
                });
            });
        }

        if (!empty($input['user_doctors'])) {
            $query->whereHas('medicalhistory', function ($q) use ($input) {
               $q->where('user_id','=', $input['user_doctors']);
            });
        }

        $query = $query->whereNull('deleted_at')->orderBy('updated_at', 'DESC');
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
