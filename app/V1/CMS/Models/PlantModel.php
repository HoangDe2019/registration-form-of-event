<?php
/**
 * User: Administrator
 * Date: 29/09/2018
 * Time: 12:32 AM
 */

namespace App\V1\CMS\Models;


use App\Plant;
use App\Supports\OFFICE_Error;
use Illuminate\Support\Facades\DB;

class PlantModel extends AbstractModel
{
    /**
     * PlantModel constructor.
     * @param Plant|null $model
     */
    public function __construct(Plant $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $param = [
            'code' => strtoupper(array_get($input, 'code')),
            'name' => $input['name'],
            'description' => array_get($input, 'description', null),
            'group_id' => array_get($input, 'group_id'),
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
                $plant = $this->update($param);
            } else {
                // Create
                $plant = $this->create($param);
            }

            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message']);
        }

        return $plant;
    }
}