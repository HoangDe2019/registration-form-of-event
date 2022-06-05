<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\DiseasesDiagnosed;
use App\Supports\Message;
use App\MedicalHistory;
use App\OFFICE;
use App\TestResult;
use App\User;
use App\Profile;
use App\HealthBookRecord;

class TestResultModel extends AbstractModel
{
    public function __construct(TestResult $model = null)
    {
        parent::__construct($model);
    }

    public function search($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);

        if (!empty($input['name'])) {
            $query = $query->where('name', 'like', "%{$input['name']}%");
        }

        if (!empty($input['analysis_name'])) {
            $query->whereHas('analysis', function ($q) use ($input) {
                $q->where('name',  'like', "%{$input['analysis_name']}%");
            });
        }

        if (!empty($input['health_record_book_id'])) {
            $query->whereHas('medical_history', function ($q) use ($input) {
                $q->where('health_record_book_id', 'like', "%{$input['health_record_book_id']}%");
            });
        }

        if (!empty($input['full_name_doctors'])) {
            $query = $query->whereHas('medical_history', function ($q) use ($input) {
                $q->whereHas('user', function ($r) use ($input) {
                    $r->whereHas('profile', function ($search) use ($input) {
                        $search->where('full_name', 'like', "%{$input['full_name_doctors']}%");
                    });
                });
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
