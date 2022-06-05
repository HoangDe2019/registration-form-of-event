<?php
/**
 * User: Administrator
 * Date: 28/09/2018
 * Time: 11:03 PM
 */

namespace App\V1\CMS\Models;


use App\Education;
use App\OFFICE;
use App\Profile;
use App\Supports\Message;
use App\User;

class EducationModel extends AbstractModel
{
    /**
     * CityModel constructor.
     * @param Education|null $model
     */
    public function __construct(Education $model = null)
    {
        parent::__construct($model);
    }
}