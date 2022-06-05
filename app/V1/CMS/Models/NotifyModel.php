<?php
/**
 * User: Administrator
 * Date: 17/10/2018
 * Time: 12:04 AM
 */

namespace App\V1\CMS\Models;

use App\OFFICE;
use App\Notify;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use Illuminate\Support\Facades\DB;

class NotifyModel extends AbstractModel
{
    /**
     * NotifyModel constructor.
     * @param Notify|null $model
     */
    public function __construct(Notify $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $notify = Notify::find($id);
            if (empty($notify)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $notify->title = array_get($input, 'title', $notify->name);
            $notify->issue_id = array_get($input, 'issue_id', $notify->issue_id);
            $notify->discuss_id = array_get($input, 'discuss_id', $notify->discuss_id);
            $notify->description = array_get($input, 'description', $notify->description);
            $notify->date = array_get($input, 'date', $notify->date);
            $notify->is_active = array_get($input, 'is_active', $notify->is_active);
            $notify->updated_at = date("Y-m-d H:i:s", time());
            $notify->updated_by = OFFICE::getCurrentUserId();
            $notify->save();
        } else {
            $param = [
                'title' => $input['title'],
                'issue_id' => array_get($input, 'issue_id', null),
                'discuss_id' => array_get($input, 'discuss_id', null),
                'description' => array_get($input, 'description', null),
                'date' => date("Y-m-d H:i:s", time()),
                'is_active' => 1,
                'updated_at' => date("Y-m-d H:i:s", time()),
                'updated_by' => OFFICE::getCurrentUserId(),
            ];
            $notify = $this->create($param);
        }
        return $notify;
    }
}