<?php
/**
 * User: Ho Sy Dai
 * Date: 9/28/2018
 * Time: 10:11 AM
 */

namespace App\V1\CMS\Models;


use App\City;

/**
 * Class CityModel
 * @package App\V1\CMS\Models
 */
class CityModel extends AbstractModel
{
    /**
     * CityModel constructor.
     * @param City|null $model
     */
    public function __construct(City $model = null)
    {
        parent::__construct($model);
    }
}