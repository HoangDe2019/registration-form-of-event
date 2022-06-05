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
class Medicine extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medicines';

    protected $fillable = [
        'medicine_origin_id',
        'code',
        'name',
        'effect',
        'is_active',
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by"
    ];


    public function medicineOrigin()
    {
        return $this->hasOne(__NAMESPACE__ . '\MedicineOrigin', 'id', 'medicine_origin_id');
    }
    public function prescriptionDetails()
    {
        return $this->hasMany(__NAMESPACE__ . '\PrescriptionDetail', 'medicine_id', 'id');
    }

}
