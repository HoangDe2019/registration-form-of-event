<?php
/**
 * User: Administrator
 * Date: 16/10/2018
 * Time: 08:14 PM
 */

namespace App\V1\CMS\Transformers\Contact;


use App\Contact;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{
    public function transform(Contact $contact)
    {
        try {
            return [
                'id' => $contact->id,
                'subject_code' => $contact->subject_code,
                'subject_name' => $contact->subject_name,
                'subject_desc' => $contact->subject_desc,
                'about_code' => $contact->about_code,
                'about_name' => $contact->about_name,
                'about_desc' => $contact->about_desc,
                'content' => $contact->content,
                'status' => $contact->status,

                'user_id' => $contact->user_id,
                'phone' => object_get($contact, 'user.phone'),
                'last_name' => object_get($contact, 'user.profile.last_name'),
                'first_name' => object_get($contact, 'user.profile.first_name'),
                'full_name' => object_get($contact, 'user.profile.full_name'),

                'is_active' => $contact->is_active,
                'updated_at' => !empty($contact->updated_at) ? date('d/m/Y H:i', strtotime($contact->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
