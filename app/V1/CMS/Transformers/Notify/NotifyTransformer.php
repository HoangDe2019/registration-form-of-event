<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 6/25/2019
 * Time: 11:25 PM
 */

namespace App\V1\CMS\Transformers\Notify;


use App\Notify;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class NotifyTransformer extends TransformerAbstract
{
    public function transform(Notify $notify)
    {
        try {
            return [
                'id' => $notify->id,
                'title' => $notify->title,
                'issue_id' => $notify->issue_id,
                'issue_name' => object_get($notify, 'issueNotify.name'),
                'discuss_id' => $notify->discuss_id,
                'discuss_name' => object_get($notify, 'discussNotify.description'),
                'discuss_issue_id' => $notify->discuss_issue_id,
                'sender_id' => $notify->sender,
                'sender_name' => object_get($notify, 'userSender.profile.full_name'),
                'sender_avatar' => url('/v0') . "/img/" . object_get($notify, 'userSender.profile.avatar'),
                'receiver_id' => $notify->receiver,
                'receiver_name' => object_get($notify, 'userReceiver.profile.full_name'),
                'receiver_avatar' => url('/v0') . "/img/" . object_get($notify, 'userReceiver.profile.avatar'),
                'description' => $notify->description,
                'date' => date('d-m-Y', strtotime($notify->date)),
                'is_active' => $notify->is_active,
                'updated_at' => !empty($notify->updated_at) ? date('d-m-Y', strtotime($notify->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}