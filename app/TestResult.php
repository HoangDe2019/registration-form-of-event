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
class TestResult extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'test_result';

    protected $fillable = [
        'medical_history_id',
        'analysis_id',
        'name',
        'image',
        'user_id',
        'description',
        "is_active",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by"
    ];

    public function analysis()
    {
        return $this->hasOne(__NAMESPACE__ . '\Analysis', 'id', 'analysis_id');
    }
    public function medical_history()
    {
        return $this->hasOne(__NAMESPACE__ . '\MedicalHistory', 'id', 'medical_history_id');
    }
}
