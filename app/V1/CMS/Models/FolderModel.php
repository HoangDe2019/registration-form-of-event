<?php


namespace App\V1\CMS\Models;


use App\Folder;
use App\OFFICE;
use App\Supports\Message;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class FolderModel extends AbstractModel
{
    public function __construct(Folder $model = null)
    {
        parent::__construct($model);
    }

    public $cate_child;

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        $folder_name = str_replace(" ", "_", $input['folder_name']);
        $folder_name = Str::ascii($folder_name);
        if ($id) {
            $folders = Folder::find($id);
            if (empty($folders)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            // $foderNameOld = $folders->folder_name;
            //  $boolFolderNameStrpos = $folders->folder_name . '/';
            $folders->folder_name = $folder_name;
            $folders->folder_key = array_get($input, 'folder_key', $folders->folder_key);
            $folders->parent_id = array_get($input, 'parent_id', $folders->parent_id);
            $folders->module_id = array_get($input, 'module_id', $folders->module_id);
            $folders->updated_at = date("Y-m-d H:i:s", time());
            $folders->updated_by = OFFICE::getCurrentUserId();
            $folders->save();
            //   $allFolder = Folder::model()->get()->toArray();
            //  $this->childElementList($allFolder, $id);

//            if (!empty($input['folder_name'])) {
//                $parentPathNew = [];
//                $listFolderNew = $this->cate_child;
//                foreach ($listFolderNew as $key => $value) {
//                    $pos = strpos($value['folder_path'], $boolFolderNameStrpos);
//                    if ($pos) {
//                        $nameNew = $folders->folder_name . '/';
//                        $itemFolderPath = str_replace($boolFolderNameStrpos, $nameNew, $value['folder_path']);
//
//                    } else {
//                        $itemFolderPath = str_replace($foderNameOld, $folders->folder_name, $value['folder_path']);
//                    }
//                    $parentPathNew[] = [
//                        'key'   => $key,
//                        'value' => $itemFolderPath,
//
//                    ];
//                }
//                $detailsParth = array_pluck($parentPathNew, 'value', 'key');
//                $fag = true;
//                foreach ($listFolderNew as $keys => $values) {
//                    if ($fag == true) {
//                        rename(public_path() . '/' . $values['folder_path'],
//                            public_path() . '/' . $detailsParth[$keys]);
//                        $values['folder_name'] = $folders->folder_name;
//                        $fag = false;
//                    }
//                    $values['folder_path'] = $detailsParth[$keys];
//                    $this->update($values);
//                }
//            }

        } else {
            $folderName = $folder_name;
            $folderPath = !empty($input['folder_path']) ? $input['folder_path'] . '/' . $folder_name : 'uploads/' . $folder_name;
            if (!empty($input['folder_path'])) {
                $folder = Folder::model()->where('folder_path', '=', $input['folder_path'])->first();
            }
            $param = [
                'folder_name' => $folderName,
                'folder_path' => $folderPath,
                'parent_id' => array_get($input, 'parent_id'),
                'module_id' => array_get($input, 'module_id'),
                'folder_key' => array_get($input, 'folder_key'),
                'is_active' => 1
            ];
            if (!empty($input['folder_path'])) {
                $path = public_path() . '/' . $input['folder_path'] . '/' . $folderName;
                File::makeDirectory($path, $mode = 0777, true, true);
            } else {
                $path = public_path() . '/uploads/' . '' . $folderName;
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $folders = $this->create($param);
        }
        return $folders;
    }

    public function childElementList(array $folders, $parentId = null)
    {
        $listFolder = [];
        $allUnset = $folders;
        $allFill = $folders;
        foreach ($allFill as $key => $value) {
            if ($value['id'] == $parentId) {
                continue;
            } else if (empty($value['parent_id'])) {
                unset($allFill[$key]);
            }
        }
        foreach ($folders as $key => $value) {
            if ($value['id'] == $parentId) {
                array_push($listFolder, $value);
                unset($allUnset[$key]);
            } else if ($value['parent_id'] == $parentId) {

                array_push($listFolder, $value);
                unset($allUnset[$key]);
            }
        }
        foreach ($allFill as $key1 => $item1) {
            {
                foreach ($allUnset as $key2 => $item2) {
                    if ($item2['parent_id'] == $item1['id']) {
                        if (in_array($item2, $listFolder)) {
                            continue;
                        }
                        array_push($listFolder, $item2);
                    }
                }
            }
        }
        $this->cate_child = $listFolder;
    }
}