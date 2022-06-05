<?php
/**
 * User: Administrator
 * Date: 28/09/2018
 * Time: 11:03 PM
 */

namespace App\V1\CMS\Models;


use App\Profile;

class ProfileModel extends AbstractModel
{
    /**
     * CityModel constructor.
     * @param Profile|null $model
     */
    public function __construct(Profile $model = null)
    {
        parent::__construct($model);
    }
}