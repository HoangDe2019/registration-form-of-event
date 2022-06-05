<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/21/2019
 * Time: 5:09 PM
 */

namespace App\V1\CMS\Models;


use App\Discuss;
use App\OFFICE;
use App\Supports\Message;
use phpDocumentor\Reflection\Types\Null_;

class DiscussModel extends AbstractModel
{
    public function __construct(Discuss $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $discuss = Discuss::find($id);
            if (empty($discuss)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $discuss->description = array_get($input, 'description', $discuss->description);
            $discuss->issue_id = array_get($input, 'issue_id', $discuss->issue_id);
            $discuss->parent_id = array_get($input, 'issue_id', $discuss->parent_id);
            $discuss->image_id = array_get($input, 'image_id', $discuss->image_id);
            $discuss->is_active = array_get($input, 'is_active', $discuss->is_active);
            $discuss->updated_at = date("Y-m-d H:i:s", time());
            $discuss->updated_by = OFFICE::getCurrentUserId();
            $discuss->save();
        } else {
            $param = [
                'issue_id' => array_get($input, 'issue_id', NULL),
                'description' => array_get($input, 'description', NULL),
                'image_id' => array_get($input, 'image_id', NULL),
                'parent_id' => array_get($input, 'parent_id', NULL),
                'is_active' => 1,
            ];
            $discuss = $this->create($param);
        }
        return $discuss;
    }
}