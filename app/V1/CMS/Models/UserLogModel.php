<?php
/**
 * User: Dai Ho
 * Date: 22-Mar-17
 * Time: 23:43
 */

namespace App\V1\CMS\Models;

use App\UserLog;

/**
 * Class UserLogModel
 *
 * @package App\V1\CMS\Models
 */
class UserLogModel extends AbstractModel
{
    public function __construct(UserLog $model = null)
    {
        parent::__construct($model);
    }
}