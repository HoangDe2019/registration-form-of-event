<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 7/2/2019
 * Time: 1:00 AM
 */

namespace App\V1\CMS\Models;

use App\ShareDrive;

class ShareDriveModel extends AbstractModel
{
    public function __construct(ShareDrive $model = null)
    {
        parent::__construct($model);
    }
}