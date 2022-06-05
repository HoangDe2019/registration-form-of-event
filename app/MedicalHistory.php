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
class MedicalHistory extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medical_histories';


    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'health_record_book_id',
        'prescription_id',
        'numbers',
        'checkin',
        'symptom',
        'follow_up',
        'description',
        'is_active',
        'deleted',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];

    public function healthrecordbook()
    {
        return $this->hasOne(__NAMESPACE__ . '\HealthBookRecord', 'id', 'health_record_book_id');
    }
    public function user()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'user_id');
    }
    public function prescription()
    {
        return $this->hasOne(__NAMESPACE__ . '\Prescription', 'id', 'prescription_id');
    }
    public function diseases_diagnosed()
    {
        return $this->hasMany(__NAMESPACE__ . '\DiseasesDiagnosed', 'medical_history_id', 'id');
    }
    public function test_result()
    {
        return $this->hasMany(__NAMESPACE__ . '\TestResult', 'medical_history_id', 'id');
    }
}
