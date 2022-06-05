<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 6/3/2019
 * Time: 10:11 PM
 */

namespace App\V1\CMS\Transformers\File;


use App\LogFileFolder;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class logFileTransformer extends TransformerAbstract
{
    public function transform(LogFileFolder $file)
    {
        try {
            $folder_path = object_get($file, 'files.folder.folder_path');
            if (!empty($folder_path)) {
                $folder_path = str_replace("/", ",", $folder_path);
            } else {
                $folder_path = "uploads";
            }
            $folder_path = url('/v0') . "/img/" . $folder_path;

            return [
                'id' => $file->id,
                'file_id' => $file->file_id,
                'code' => object_get($file, 'files.code'),
                'action' => $file->action,
                'description' => $file->description,
                'file_name' => object_get($file, 'files.file_name'),
                'title' => object_get($file, 'files.title'),
                'folder_id' => object_get($file, 'files.folder.folder_id'),
                'folder_name' => object_get($file, 'files.folder.folder_name'),
                'folder_path' => object_get($file, 'files.folder.folder_path'),
                'file' => !empty($folder_path) ? $folder_path . ',' . object_get($file, 'files.file_name') : null,
                'extension' => object_get($file, 'files.extension'),
                'size' => !empty(object_get($file, 'files.size')) ? (object_get($file, 'files.size')) . ' ' . 'byte' : null,
                'is_active' => $file->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($file->created_at)),
                'created_by' => object_get($file, 'createdBy.profile.full_name'),
                'updated_at' => date('d/m/Y H:i', strtotime($file->updated_at)),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}