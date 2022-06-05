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
class Analysis extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'analysis';

    protected $fillable = [
        'name',
        "description",
        "is_active",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by"
    ];


    public function test_result()
    {
        return $this->hasMany(__NAMESPACE__ . '\TestResult', 'analysis_id', 'id');
    }
    /*
    Not FK in table week
      public function companyId()
      {
          return $this->hasOne(__NAMESPACE__ . '\Company', 'id', 'company_id');
      }
    */
}
