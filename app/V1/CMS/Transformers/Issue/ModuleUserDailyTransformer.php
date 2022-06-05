<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/16/2019
 * Time: 1:38 PM
 */

namespace App\V1\CMS\Transformers\Issue;


use App\Issue;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class ModuleUserDailyTransformer extends TransformerAbstract
{
    public function transform(Issue $issue)
    {
        try {
            return [
                'user_name' => object_get($issue, 'user.profile.full_name', null),
                'issueToday' => $issue->issueToday,
                'workTime' => $issue->workTime,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}