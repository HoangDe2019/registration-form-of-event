<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/21/2019
 * Time: 5:06 PM
 */

namespace App;


class Discuss extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discuss';

    /**
     * @var array
     */
    protected $fillable = [
        'issue_id',
        'description',
        'parent_id',
        'image_id',
        'count_like',
        'is_active',
        'deleted',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];

    public function createdBy()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'created_by');
    }

    public function updatedBy()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'updated_by');
    }

    public function file()
    {
        return $this->hasOne(__NAMESPACE__ . '\Files', 'id', 'image_id');
    }
}