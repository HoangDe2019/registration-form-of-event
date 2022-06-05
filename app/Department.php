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

class Department extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departments';

    protected $fillable = [
        'code',
        "name",
        "phone",
        "description",
        "company_id",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by"
    ];
    public function companyId()
    {
        return $this->hasOne(__NAMESPACE__ . '\Company', 'id', 'company_id');
    }

    public function user()
    {
        return $this->hasMany(__NAMESPACE__ . '\User', 'user_id', 'id');
    }
    public function disease()
    {
        return $this->hasMany(__NAMESPACE__ . '\Diseases', 'department_id', 'id');
    }
}
