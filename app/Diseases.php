<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:26:12
 * @modify date 2020-12-03 12:26:12
 * @desc [description]
 */

namespace App;


/**
 * Class Company
 * @package App
 */

class Diseases extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'diseases';

    protected $fillable = [
        'department_id',
        "code",
        "name",
        "symptom",
        "phase",
        "description",
        "is_active",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by"
    ];
    public function department()
    {
        return $this->hasOne(__NAMESPACE__ . '\Department', 'id', 'department_id');
    }

    public function diseases_diagnosed()
    {
        return $this->hasMany(__NAMESPACE__ . '\DiseasesDiagnosed', 'diseases_id', 'id');
    }
}
