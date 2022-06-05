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
class Prescription extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prescriptions';

    protected $fillable = [
        "code",
        'action',
        "is_active",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by"
    ];

    public function prescriptionDetails()
    {
        return $this->hasMany(__NAMESPACE__ . '\PrescriptionDetail', 'prescription_id', 'id');
    }
    public function medicalhistory()
    {
        return $this->hasOne(__NAMESPACE__ . '\MedicalHistory', 'prescription_id', 'id');
    }
}
