<?php
/**
 * User: Ho Sy Dai
 * Date: 10/30/2018
 * Time: 3:47 PM
 */

namespace App\V1\CMS\Models;


use App\PlantBug;
use App\Supports\OFFICE_Error;
use Illuminate\Support\Facades\DB;

class PlantBugModel extends AbstractModel
{
    /**
     * PlantBugModel constructor.
     * @param PlantBug|null $model
     */
    public function __construct(PlantBug $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $param = [
            'code' => array_get($input, 'code'),
            'name' => array_get($input, 'name'),
            'description' => array_get($input, 'description', null),
            'suggest_fix' => array_get($input, 'suggest_fix', null),
            'is_active' => 1,
        ];
        try {
            DB::beginTransaction();
            $id = !empty($input['id']) ? $input['id'] : 0;
            $this->checkUnique(['code' => $input['code'],], $id);

            if ($id) {
                $param['id'] = $input['id'];
                $info = $this->update($param);
            } else {
                // Create
                $info = $this->create($param);
            }
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message']);
        }
        return $info;
    }
}
