<?php
/**
 * User: Dai Ho
 * Date: 22-Mar-17
 * Time: 23:43
 */

namespace App;

/**
 * Class RolePermission
 *
 * @package App
 */
class BookBefore extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'book_before';


    /**
     * @var array
     */
    protected $fillable = [
        'medical_schedule_id',
        'user_id',
        'times_of_day_id',
        'name',
        'time',
        'from_book',
        'to_book',
        'state',
        'is_active',
        'description',
        'deleted',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'user_id');
    }
    public function schedule()
    {
        return $this->hasOne(__NAMESPACE__ . '\MedicalSchedule', 'id', 'medical_schedule_id');
    }

    public function timeofday()
    {
        return $this->hasOne(__NAMESPACE__ . '\TimeOfDay', 'id', 'times_of_day_id');
    }
}
