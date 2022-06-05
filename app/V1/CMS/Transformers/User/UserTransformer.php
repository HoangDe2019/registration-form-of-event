<?php

/**
 * User: Dai Ho
 * Date: 22-Mar-17
 * Time: 23:43
 */

namespace App\V1\CMS\Transformers\User;

use App\Supports\OFFICE_Error;
use App\User;
//use Illuminate\Support\Facades\URL;
use Illuminate\Support\Arr;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer
 *
 * @package App\V1\CMS\Transformers
 */
class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        try {
            /*
            $profiles = Arr::get($user,'profile', []);
            if(!empty($profiles)) {
                $profiles = [
//                    'id' => $profiles['id'],
                     $profiles['first_name'],
                    $profiles['last_name'],
                    // $profiles['short_name'],
                $profiles['full_name'],
                   $profiles['address'],
                    $profiles['phone'],
                ];
            }*/
            return [
                'id' => $user->id,
                'code' => $user->code,
                'phone' => $user->phone,
                'email' => $user->email,
                'username' => $user->username,
  //              'profiles' => $profiles,
                'role_id' => $user->role_id,
                'created_at' => date('d/m/Y H:i', strtotime($user->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($user->updated_at)),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
