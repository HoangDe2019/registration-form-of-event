<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 7/2/2019
 * Time: 12:57 AM
 */

namespace App;


class ShareDrive extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'share-drive';

    protected $fillable = [
        'file_id',
        'file_id',
        'action',
        'is_active',
        'user_id',
        'deleted',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];


}