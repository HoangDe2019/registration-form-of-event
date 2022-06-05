<?php
/**
 * User: Dai Ho
 * Date: 22-Mar-17
 * Time: 23:43
 */

namespace App;

class Education extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'educations';


    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'degree',
        'college_or_universiy',
        'year_of_completion',
        'is_active',
        'deleted',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];

    public function user()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'user_id');
    }
}
