<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/22/2019
 * Time: 11:22 AM
 */

namespace App;


class Module extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'modules';

    /**
     * @var array
     */
    protected $casts = [
        'user_module_details' => 'json',
    ];

    protected $fillable = [
        'code',
        'name',
        'description',
        'user_module_details',
        'is_active',
        'deleted',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'module_has_users');
    }
}