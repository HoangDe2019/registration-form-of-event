<?php

/**
 * @author [author]
 * @email [linhfish10c1@gmail.com]
 * @create date 2020-12-03 12:22:49
 * @modify date 2020-12-03 12:22:49
 * @desc [description]
 */

namespace App\V1\CMS\Models;

use App\Supports\Message;
use App\Analysis;
use App\OFFICE;
use phpDocumentor\Reflection\Types\True_;
use function Aws\boolean_value;


class AnalysisModel extends AbstractModel
{
    public function __construct(Analysis $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $analysis = Analysis::find($id);
            if (empty($analysis)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $analysis->name = array_get($input, 'name', $analysis->name);
            $analysis->description = array_get($input, 'description', NULL);
            $analysis->is_active = array_get($input, 'is_active', $analysis->is_active);
            $analysis->updated_at = date("d-m-Y H:i:s", time());
            $analysis->updated_by = OFFICE::getCurrentUserId();
            $analysis->save();
        } else {
            $param = [
                'name' => $input['name'],
                'is_active' => 1,
                'description' => array_get($input, 'description'),
            ];

            $analysis = $this->create($param);
        }
        return $analysis;
    }
}
