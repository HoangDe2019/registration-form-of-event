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
class Company extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    protected $fillable = [
        'code',
        "name",
        "email",
        "address",
        "tax",
        "phone",
        "description",
        "avatar_id",
        "avatar",
        "is_active",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];
}
