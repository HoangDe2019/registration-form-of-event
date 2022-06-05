<?php


namespace App\V1\CMS\Controllers;


use App\V1\CMS\Models\LogFileFolderModel;

class LogFileFolderController extends BaseController
{
    protected $model;

    /**
     * ModuleCategoryController constructor.
     */

    public function __construct()
    {
        $this->model = new LogFileFolderModel();
    }
}