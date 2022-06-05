<?php
/**
 * User: Administrator
 * Date: 29/09/2018
 * Time: 12:32 AM
 */

namespace App\V1\CMS\Models;


use App\PlantGroup;
use App\Supports\OFFICE_Error;
use Illuminate\Support\Facades\DB;

class PlantGroupModel extends AbstractModel
{
    /**
     * PlantGroupModel constructor.
     * @param PlantGroup|null $model
     */
    public function __construct(PlantGroup $model = null)
    {
        parent::__construct($model);
    }

    /**
     * @param $input
     * @return mixed
     * @throws \Exception
     */
    public function upsert($input)
    {
        $param = [
            'code' => strtoupper(array_get($input, 'code')),
            'name' => $input['name'],
            'info_name' => $input['name'],
            'description' => array_get($input, 'description', null),
            'is_active' => 1,
        ];

        try {
            DB::beginTransaction();
            $id = !empty($input['id']) ? $input['id'] : 0;
            if (!empty($input['code'])) {
                $this->checkUnique([
                    'code' => strtoupper($input['code']),
                ], $id);
            }

            if ($id) {
                $param['id'] = $input['id'];
                $plantGroup = $this->update($param);
            } else {
                // Create
                $plantGroup = $this->create($param);
            }

            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message']);
        }

        return $plantGroup;
    }
}