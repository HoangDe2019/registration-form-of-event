<?php
/**
 * User: dai.ho
 * Date: 3/29/2019
 * Time: 04:42 PM
 */

namespace App\V1\CMS\Traits;


use App\Supports\Message;
use Illuminate\Support\Facades\DB;

trait ControllerTrait
{
    /**
     * @param $id
     * @param $tables
     *
     * @return bool
     * @throws \Exception
     */
    private function checkForeignTable($id, $tables)
    {
        if (empty($tables)) {
            return true;
        }

        $result = "";

        foreach ($tables as $table_key => $table) {
            $temp = explode(".", $table_key);
            $table_name = $temp[0];
            $foreign_key = !empty($temp[1]) ? $temp[1] : 'id';
            $data = DB::table($table_name)->where($foreign_key, $id)->first();
            if (!empty($data)) {
                $result .= "$table; ";
            }
        }

        $result = trim($result, "; ");

        if (!empty($result)) {
            return $this->response->errorBadRequest(Message::get("R004", $result));
        }

        return true;
    }
}