<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/22/2019
 * Time: 11:42 AM
 */

namespace App\V1\CMS\Transformers\Module;


use App\Module;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class ModuleTransformer extends TransformerAbstract
{
    /**
     * @param Module $module
     * @return array
     * @throws \Exception
     */

    public function transform(Module $module)
    {
        try {
            return [
                'id' => $module->id,
                'name' => $module->name,
                'code' => $module->code,
                'description' => $module->description,
                'user_module_details' => $module->user_module_details ?? [],
                'is_active' => $module->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($module->created_at)),
                'updated_at' => !empty($module->updated_at) ? date('d/m/Y H:i',
                    strtotime($module->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}