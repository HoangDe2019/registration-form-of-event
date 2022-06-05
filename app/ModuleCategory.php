<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 11:25 AM
 */

namespace App;


class ModuleCategory extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'module_category';

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'module_id',
        'is_active',
        'deleted',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];

    public function module()
    {
        return $this->hasOne(Module::class, 'id', 'module_id');
    }
}