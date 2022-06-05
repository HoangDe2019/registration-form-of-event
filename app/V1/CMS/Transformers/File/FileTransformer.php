<?php


namespace App\V1\CMS\Transformers\File;

//e fix tiêp di
use App\Files;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class FileTransformer extends TransformerAbstract
{
    public function transform(Files $file)
    {
        try {
            $folder_path = object_get($file, 'folder.folder_path');
            if (!empty($folder_path)) {
                $folder_path = str_replace("/", ",", $folder_path);
            } else {
                $folder_path = "uploads";
            }
            $folder_path = url('/v0') . "/img/" . $folder_path;

            return [
                'id' => $file->id,
                'code' => $file->code,
                'file_name' => $file->file_name,
                'title' => $file->title,
                'folder_id' => $file->folder_id,
                'folder_name' => object_get($file, 'folder.folder_name'),
                'folder_path' => object_get($file, 'folder.folder_path'),
                'file' => !empty($folder_path) ? $folder_path . ',' . $file->file_name : null,
                'extension' => $file->extension,
                'size' => !empty($file->size) ? $file->size . ' ' . 'byte' : null,
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