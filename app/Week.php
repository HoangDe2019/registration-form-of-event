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
class Week extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'week';

    protected $fillable = [
        'code',
        "checkin",
        "checkout",
        "description",
        "state",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by"
    ];

    public function medical_schedules()
    {
        return $this->hasMany(__NAMESPACE__ . '\MedicalSchedule', 'week_id', 'id');
    }
    /*
    Not FK in table week
      public function companyId()
      {
          return $this->hasOne(__NAMESPACE__ . '\Company', 'id', 'company_id');
      }
    */
}
