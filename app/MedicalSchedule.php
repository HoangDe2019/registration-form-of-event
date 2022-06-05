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
class MedicalSchedule extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medical_schedules';

    protected $fillable = [
        'user_id',
        "week_id",
        "checkin",
        "session",
        "is_active",
        "description",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by"
    ];

    public function user()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'user_id');
    }
    public function week()
    {
        return $this->hasOne(__NAMESPACE__ . '\Week', 'id', 'week_id');
    }
    public function bookBefore()
    {
        return $this->hasMany(__NAMESPACE__ . '\BookBefore', 'medical_schedule_id', 'id');
    }
}
