<?php
/**
 * User: Administrator
 * Date: 12/10/2018
 * Time: 06:34 PM
 */

namespace App\V1\CMS\Models;


use App\DiseasesDiagnosed;

class DiseasesDiagnosedModel extends AbstractModel
{
    public function __construct(DiseasesDiagnosed $model = null)
    {
        parent::__construct($model);
    }
}