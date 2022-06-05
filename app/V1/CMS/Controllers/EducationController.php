<?php

namespace App\V1\CMS\Controllers;


use App\OFFICE;
use App\Supports\OFFICE_Email;
use App\User;
use App\Supports\Log;

use App\Supports\OFFICE_Error;
use App\Supports\Message;
use App\V1\CMS\Models\EducationModel;
use App\V1\CMS\Models\HealthBookRecordModel;
//use App\V1\CMS\Models\UserModel;
use App\V1\CMS\Transformers\HealthBookRecord\HealthBookRecordTransformer;
use App\V1\CMS\Transformers\User\UserTransformer;
use App\V1\CMS\Validators\UserCreateValidator;
use App\V1\CMS\Validators\HealthBookRecordUpdateValidator;
use App\V1\CMS\Validators\UserUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * Class UserController
 *
 * @package App\V1\CMS\Controllers
 */
class EducationController extends BaseController
{

    /**
     * @var HealthBookRecordModel
     */
    protected $model;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->model = new EducationModel();
    }

    /**
     * @param UserTransformer $userTransformer
     * @return \Dingo\Api\Http\Response
     */
}
