<?php

namespace App\V1\CMS\Transformers\Folder;


use App\File;
use App\Folder;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class FolderTransformer extends TransformerAbstract
{
    public function transform(Folder $folder)
    {
        $folders = [];
        $folders[] = [
            'id' => $folder->id,
            'folder_name' => $folder->folder_name,
            'folder_path' => $folder->folder_path,
            'folder_key' => $folder->folder_key,
            'parent_id' => $folder->parent_id,
            'is_active' => $folder->is_active,
            'created_by' => object_get($folder, 'createdBy.profile.full_name'),
            'created_at' => date('d/m/Y H:i', strtotime($folder->created_at)),
            'updated_at' => date('d/m/Y H:i', strtotime($folder->updated_at)),
        ];

        $file = File::model()->orderBy('id', 'DESC')->get()->take(20);
        $data_file = [];
        foreach ($file as $item) {
            $folder_path = object_get($item, 'folder.folder_path');
            if (!empty($folder_path)) {
                $folder_path = str_replace("/", ",", $folder_path);
            } else {
                $folder_path = "uploads";
            }
            $folder_path = url('/v0') . "/img/" . $folder_path;

            $data_file[] = [
                'id' => $item->id,
                'code' => $item->code,
                'file_name' => $item->file_name,
                'title' => $item->title,
                'folder_id' => $item->folder_id,
                'folder_name' => object_get($item, 'folder.folder_name'),
                'folder_path' => object_get($item, 'folder.folder_path'),
                'file' => !empty($folder_path) ? $folder_path . ',' . $item->file_name : null,
                'extension' => $item->extension,
                'size' => !empty($item->size) ? $item->size . ' ' . 'byte' : null,
                'is_active' => $item->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($item->created_at)),
                'created_by' => object_get($item, 'createdBy.profile.full_name'),
                'updated_at' => date('d/m/Y H:i', strtotime($item->updated_at)),
            ];
        }
        try {
            return [
                'folder' => $folders,
                'file' => $data_file
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}