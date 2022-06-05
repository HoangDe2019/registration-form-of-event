<?php
/**
 * User: Dai Ho
 * Date: 22-Mar-17
 * Time: 23:43
 */

namespace App;

class Profile extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';


    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'email',
        'first_name',
        'last_name',
        'full_name',
        'address',
        'phone',
        'birthday',
        'avatar',
        'genre',
        'blood_group',
        'id_number',
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
