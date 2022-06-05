<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 6/1/2019
 * Time: 3:55 PM
 */

namespace App\V1\CMS\Transformers\Folder;


use App\Folder;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class FolderDetailTransformer extends TransformerAbstract
{
    public function transform(Folder $folder)
    {

        $detailsFolder = Folder::model()->where('parent_id', $folder->id)->get();
        $details_files = $folder->file;
        $data_folder = [];
        foreach ($detailsFolder as $item) {
            $data_folder[] = [
                'id' => $item->id,
                'folder_name' => $item->folder_name,
                'folder_path' => $item->folder_path,
                'folder_key' => $item->folder_key,
                'is_active' => $item->is_active,
                'parent_id' => $item->parent_id,
                'details_file' => $this->getDataFile($details_files),
                'created_by' => object_get($folder, 'createdBy.profile.full_name'),
                'created_at' => date('d/m/Y H:i', strtotime($folder->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($folder->updated_at)),
            ];
        }
        try {
            return [
                'id' => $folder->id,
                'folder_name' => $folder->folder_name,
                'folder_path' => $folder->folder_path,
                'folder_key' => $folder->folder_key,
                'is_active' => $folder->is_active,
                'parent_id' => $folder->parent_id,
                'details_file' => $this->getDataFile($folder->file),
                'details_folder' => $data_folder,
                'created_by' => object_get($folder, 'createdBy.profile.full_name'),
                'created_at' => date('d/m/Y H:i', strtotime($folder->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($folder->updated_at)),
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }

    public function getDataFile($details_files)
    {
        $data = [];
        foreach ($details_files as $detail) {

            $folder_path = object_get($detail, 'folder.folder_path');
            if (!empty($folder_path)) {
                $folder_path = str_replace("/", ",", $folder_path);
            } else {
                $folder_path = "uploads";
            }
            $folder_path = url('/v0') . "/img/" . $folder_path;

            $data[] = [
                'id' => $detail->id,
                'code' => $detail->code,
                'file_name' => $detail->file_name,
                'title' => $detail->title,
                'folder_id' => $detail->folder_id,
                'folder_name' => object_get($detail, 'folder.folder_name'),
                'folder_path' => object_get($detail, 'folder.folder_path'),
                'file' => !empty($folder_path) ? $folder_path . ',' . $detail->file_name : null,
                'extension' => $detail->extension,
                'size' => !empty($detail->size) ? $detail->size . ' ' . 'byte' : null,
                'is_active' => $detail->is_active,
                'created_at' => date('d/m/Y H:i', strtotime($detail->created_at)),
                'created_by' => object_get($detail, 'createdBy.profile.full_name'),
                'updated_at' => date('d/m/Y H:i', strtotime($detail->updated_at)),
            ];
        }
        return $data;
    }
}