<?php


namespace App\V1\CMS\Models;


use App\File;
use App\OFFICE;
use App\Supports\Message;

class FilesModel extends AbstractModel
{
    public function __construct(File $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $files = File::find($id);
            if (empty($files)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $files->title = array_get($input, 'title', $files->title);
            $files->folder_id = array_get($input, 'folder_id', $files->folder_id);
            $files->updated_at = date("Y-m-d H:i:s", time());
            $files->updated_by = OFFICE::getCurrentUserId();
            $files->save();
        }
        return $files;
    }
}