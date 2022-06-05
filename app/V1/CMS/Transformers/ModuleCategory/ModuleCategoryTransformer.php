<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 11:31 AM
 */

namespace App\V1\CMS\Transformers\ModuleCategory;

use App\ModuleCategory;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class ModuleCategoryTransformer extends TransformerAbstract
{
    /**
     * @param ModuleCategory $moduleCategory
     * @return array
     * @throws \Exception
     */

    public function transform(ModuleCategory $moduleCategory)
    {
        try {
            return [
                'id' => $moduleCategory->id,
                'name' => $moduleCategory->name,
                'code' => $moduleCategory->code,
                'description' => $moduleCategory->description,
                'module_id' => $moduleCategory->module_id,
                'module_code' => object_get($moduleCategory, 'module.code'),
                'module_name' => object_get($moduleCategory, 'module.name'),
                'is_active' => $moduleCategory->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($moduleCategory->created_at)),
                'updated_at' => !empty($moduleCategory->updated_at) ? date('d/m/Y H:i',
                    strtotime($moduleCategory->updated_at)) : null,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}