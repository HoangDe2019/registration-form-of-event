<?php


namespace App\V1\CMS\Transformers\Module;


use App\ModuleHasUser;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class ModuleHasUserTransformer extends TransformerAbstract
{
    /**
     * @param ModuleHasUser $moduleHasUser
     * @return array
     * @throws \Exception
     */

    public function transform(ModuleHasUser $moduleHasUser)
    {
        try {
            return [
                'id' => $moduleHasUser->user_id,
                'name' => $moduleHasUser->user_name,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}