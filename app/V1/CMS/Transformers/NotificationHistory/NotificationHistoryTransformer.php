<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 3/29/2019
 * Time: 2:59 PM
 */

namespace App\V1\CMS\Transformers\NotificationHistory;


use App\NotificationHistory;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class NotificationHistoryTransformer extends TransformerAbstract
{
    public function transform(NotificationHistory $notificationHistory)
    {
        try {
            return [
                'id' => $notificationHistory->id,
                'title' => $notificationHistory->title,
                'body' => $notificationHistory->body,
                'message' => $notificationHistory->message,
                'type' => $notificationHistory->type,
                'extradata' => $notificationHistory->extradata,
                'receiver' => $notificationHistory->receiver,
                'action' => $notificationHistory->action,
                'item_id' => $notificationHistory->item_id,
                'created_at' => date('d-m-Y', strtotime($notificationHistory->created_at)),
                'updated_at' => strtotime($notificationHistory->updated_at) > 0 ? date('d-m-Y', strtotime($notificationHistory->updated_at)) : '',
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}