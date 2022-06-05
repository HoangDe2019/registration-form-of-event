<?php
/**
 * User: Dai Ho
 * Date: 22-Mar-17
 * Time: 23:43
 */

namespace App;

class UserLog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'action',
        'target',
        'ip',
        'description',
        'deleted',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'created_by');
    }
}
