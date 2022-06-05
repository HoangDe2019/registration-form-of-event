<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/13/2019
 * Time: 9:34 PM
 */

namespace App\V1\CMS\Transformers\Log;


use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\UserLog;
use League\Fractal\TransformerAbstract;

class LogUserTransformer extends TransformerAbstract
{
    public function transform(UserLog $userLog)
    {
        try {
            $name = trim(object_get($userLog, 'user.profile.full_name', null));
            return [
                'user_name' => $name,
                'action' => Message::get("L-" . $userLog->action),
                'full_message' => Log::message($name, $userLog->action, $userLog->target, $userLog->description),
                'updated_at' => $userLog->updated_at->format('Y-m-d H:i:s'),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}