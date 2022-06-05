<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 7/2/2019
 * Time: 1:02 AM
 */

namespace App\V1\CMS\Controllers;


use App\V1\CMS\Models\ShareDriveModel;

class ShareDriveController extends BaseController
{
    protected $model;

    /**
     * ShareDriveController constructor.
     */
    public function __construct()
    {
        $this->model = new ShareDriveModel();
    }
}