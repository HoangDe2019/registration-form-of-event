<?php


namespace App\V1\CMS\Controllers;


use App\File;
use App\Folder;
use App\LogFileFolder;
use App\OFFICE;
use App\Profile;
use App\ShareDrive;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\FolderModel;
use App\V1\CMS\Models\LogFileFolderModel;
use App\V1\CMS\Models\ShareDriveModel;
use App\V1\CMS\Transformers\Folder\FolderDetailTransformer;
use App\V1\CMS\Transformers\Folder\FolderTransformer;
use App\V1\CMS\Validators\FolderCreateValidator;
use App\V1\CMS\Validators\FolderFileShareValidator;
use App\V1\CMS\Validators\FolderUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FolderController extends BaseController
{
    public function __construct()
    {
        $this->model = new FolderModel();
    }

    /**
     * @param Request $request
     * @param FolderTransformer $folderTransformer
     * @return \Dingo\Api\Http\Response
     */

    public function search(Request $request, FolderTransformer $folderTransformer)
    {
        $input = $request->all();
        $user_id = OFFICE::getCurrentUserId();
//        if (!empty($input['name'])) {
//            $folder = Folder::model()->whereNull('parent_id')
//                ->where('created_by', OFFICE::getCurrentUserId())
//                ->where('folder_name', 'like', "%{$input['name']}%")
//                ->orwhere('share_user_id', 'like', "%{$user_id}%")
//                ->orderBy('id', 'DESC')->get();
//        } else {
//            $folder = Folder::model()
//                ->whereNull('parent_id')
//                ->where('created_by', OFFICE::getCurrentUserId())
//                ->orwhere('share_user_id', 'like', "%{$user_id}%")
//                ->orderBy('id', 'DESC')->get();
//        }
        if (!isset($input['module_id'])) {
            return ['data' => []];
        }
        $folder = Folder::model()
            ->whereNull('parent_id')
            ->where('module_id', "{$input['module_id']}")
            ->orderBy('id', 'DESC')->get();
        $folders = [];
        foreach ($folder as $item) {

            $folders[] = [
                'id' => $item->id,
                'folder_name' => $item->folder_name,
                'folder_path' => $item->folder_path,
                'folder_key' => $item->folder_key,
                'parent_id' => $item->parent_id,
                'module_id' => $item->module_id,
                'action' => object_get($item, 'share.action'),
                'is_active' => $item->is_active,
                'created_by' => object_get($item, 'createdBy.profile.full_name'),
                'created_at' => date('d/m/Y H:i', strtotime($item->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($item->updated_at)),
            ];

        }
//        if (!empty($input['name'])) {
//            $file = File::model()->whereNull('folder_id')
//                ->where('created_by', OFFICE::getCurrentUserId())
//                ->where('title', 'like', "%{$input['name']}%")
//                ->orwhere('share_user_id', 'like', "%{$user_id}%")
//                ->orderBy('id', 'DESC')->get();
//        } else {
//            $file = File::model()->whereNull('folder_id')
//                ->where('created_by', OFFICE::getCurrentUserId())
//                ->orwhere('share_user_id', 'like', "%{$user_id}%")
//                ->orderBy('id', 'DESC')->get();
//        }
        $file = File::model()->whereNull('folder_id')
            ->where('module_id', "{$input['module_id']}")
            ->orderBy('id', 'DESC')->get();
        $files = [];
        foreach ($file as $item) {
            $folder_path = object_get($item, 'folder.folder_path');
            if (!empty($folder_path)) {
                $folder_path = str_replace("/", ",", $folder_path);
            } else {
                $folder_path = "uploads";
            }
            $folder_path = url('/v0') . "/img/" . $folder_path;

            $user_id = OFFICE::getCurrentUserId();
            $action_share = object_get($item, 'share_file.user_id');
            $user_share = object_get($item, 'share_file.created_by');
            if ($action_share == $user_id || $item->created_by == $user_share) {
                $action = object_get($item, 'share_file.action');
            } else {
                $action = null;
            }
            $files[] = [
                'id' => $item->id,
                'code' => $item->code,
                'file_name' => $item->file_name,
                'title' => $item->title,
                'folder_id' => $item->folder_id,
                'action' => $action,
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
        // add to the list
        $data ['folders'] = $folders;
        $data ['files'] = $files;
        if (empty($data)) {
            return ['data' => []];
        }
        return response()->json(['data' => $data]);
    }

    public function detail($id)
    {
        try {
            $result = Folder::find($id);
            if (empty($result)) {
                return ["data" => []];
            } else {
                //------------------------------------- Detail Folder -------------------------------------//
                $user_id = OFFICE::getCurrentUserId();
                $folder = Folder::model()
                    ->where('parent_id', $id)
//                    ->where('created_by', $user_id)
                    ->orderBy('id', 'DESC')->get();
                $folders = [];
                foreach ($folder as $item) {
                    $folders[] = [
                        'id' => $item->id,
                        'folder_name' => $item->folder_name,
                        'folder_path' => $item->folder_path,
                        'folder_key' => $item->folder_key,
                        'parent_id' => $item->parent_id,
                        'is_active' => $item->is_active,
                        'action' => object_get($item, 'share.action'),
                        'created_by' => object_get($item, 'createdBy.profile.full_name'),
                        'created_at' => date('d/m/Y H:i', strtotime($item->created_at)),
                        'updated_at' => date('d/m/Y H:i', strtotime($item->updated_at)),
                    ];
                }
                $file = File::model()->where('folder_id', $id)->orderBy('id', 'DESC')
                    ->where('created_by', OFFICE::getCurrentUserId())
                    ->where('is_active', 1)
                    ->get();
                $files = [];
                foreach ($file as $item) {
                    $folder_path = object_get($item, 'folder.folder_path');
                    if (!empty($folder_path)) {
                        $folder_path = str_replace("/", ",", $folder_path);
                    } else {
                        $folder_path = "uploads";
                    }
                    $folder_path = url('/v0') . "/img/" . $folder_path;

                    $files[] = [
                        'id' => $item->id,
                        'code' => $item->code,
                        'file_name' => $item->file_name,
                        'title' => $item->title,
                        'folder_id' => $item->folder_id,
                        'folder_name' => object_get($item, 'folder.folder_name'),
                        'folder_path' => object_get($item, 'folder.folder_path'),
                        'action' => object_get($item, 'share_file.action'),
                        'file' => !empty($folder_path) ? $folder_path . ',' . $item->file_name : null,
                        'extension' => $item->extension,
                        'size' => !empty($item->size) ? $item->size . ' ' . 'byte' : null,
                        'is_active' => $item->is_active,
                        'created_at' => date('d/m/Y H:i', strtotime($item->created_at)),
                        'created_by' => object_get($item, 'createdBy.profile.full_name'),
                        'updated_at' => date('d/m/Y H:i', strtotime($item->updated_at)),
                    ];
                }
                // add to the list
                $data ['folders'] = $folders;
                $data ['files'] = $files;

                if (empty($data)) {
                    return ['data' => ''];
                }
                return response()->json(['data' => $data]);
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
    }

    public function create(
        Request $request,
        FolderCreateValidator $folderCreateValidator
    )
    {
        $input = $request->all();
        $folderCreateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::create($this->model->getTable(), $result->folder_name);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("folders.create-success", $result->folder_name)];
    }

    public function update(
        $id,
        Request $request,
        FolderUpdateValidator $folderUpdateValidator
    )
    {
        $input = $request->all();
        $input['id'] = $id;
        $folderUpdateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            Log::update($this->model->getTable(), $result->folder_name);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("folders.update-success", $result->folder_name)];
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = Folder::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            // 1. Delete Department
            $result->delete();
            Log::delete($this->model->getTable(), $result->folder_name);
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("folders.delete-success", $result->folder_name)];
    }

    public function moveFolder($id, Request $request)
    {
        $input = $request->all();
        $input['id'] = $id;
        $result = $this->model->upsert($input);
        //------------------------------------- Detail Folder -------------------------------------//
        $folder = Folder::model()->where('parent_id', $id)->orderBy('id', 'DESC')->get();
        $folders = [];
        foreach ($folder as $item) {
            $folders[] = [
                'id' => $item->id,
                'folder_name' => $item->folder_name,
                'folder_path' => $item->folder_path,
                'folder_key' => $item->folder_key,
                'parent_id' => $item->parent_id,
                'is_active' => $item->is_active,
                'created_by' => object_get($item, 'createdBy.profile.full_name'),
                'created_at' => date('d/m/Y H:i', strtotime($item->created_at)),
                'updated_at' => date('d/m/Y H:i', strtotime($item->updated_at)),
            ];

        }
        $file = File::model()->where('folder_id', $id)->orderBy('id', 'DESC')->get()->take(20);
        $files = [];
        foreach ($file as $item) {
            $folder_path = object_get($item, 'folder.folder_path');
            if (!empty($folder_path)) {
                $folder_path = str_replace("/", ",", $folder_path);
            } else {
                $folder_path = "uploads";
            }
            $folder_path = url('/v0') . "/img/" . $folder_path;

            $files[] = [
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
        // add to the list
        $data = [];
        $data[] = [
            'folders' => $folders,
            'files' => $files,
        ];
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function shareDrive(Request $request)
    {
        $input = $request->all();
        if (empty($input)) {
            return ["data" => []];
        } else
            try {
                $implode_user = implode(',', $input['user_id']);
                // Update file or folder
                if (!empty($input['file_id'])) {

                    foreach ($input['file_id'] as $item) {
                        // share file user id
                        $file = File::model()->where('id', $item)->first();
                        $all_userDB = explode(",", $file->share_user_id);
                        if (!empty($file->share_user_id)) {
                            $all_user = $this->checkUser($all_userDB, $input['user_id']);
                        } else {
                            $all_user = $implode_user;
                        }
                        File::where('id', $item)->update(
                            [
                                'share_user_id' => $all_user,
                                'updated_by' => OFFICE::getCurrentUserId(),
                                'updated_at' => date("Y-m-d", time()),
                            ]);
                        // update order save file
                        foreach ($input['user_id'] as $user_id) {
                            $allFileDetail = ShareDrive::model()
                                ->where('file_id', $item)
                                ->where('user_id', $user_id)->first();
                            if (empty($allFileDetail)) {
                                $ShareDriveModel = new ShareDriveModel();
                                $param = [
                                    'file_id' => $item,
                                    'user_id' => $user_id,
                                    'action' => 'VIEW',
                                ];
                                $ShareDriveModel->create($param);
                            }
                            continue;
                        }
                    }

                } else {
                    foreach ($input['folder_id'] as $item) {
                        // share folder user id
                        $folder = Folder::model()->where('id', $item)->first();
                        $all_userDB = explode(",", $folder->share_user_id);
                        if (!empty($folder->share_user_id)) {
                            $all_user = $this->checkUser($all_userDB, $input['user_id']);
                        } else {
                            $all_user = $implode_user;
                        }
                        Folder::where('id', $item)->update(
                            [
                                'share_user_id' => $all_user,
                                'updated_by' => OFFICE::getCurrentUserId(),
                                'updated_at' => date("Y-m-d", time()),
                            ]);
                        // update order save file
                        foreach ($input['user_id'] as $user_id) {
                            $allFileDetail = ShareDrive::model()
                                ->where('folder_id', $item)
                                ->where('user_id', $user_id)->first();
                            if (empty($allFileDetail)) {
                                $ShareDriveModel = new ShareDriveModel();
                                $param = [
                                    'folder_id' => $item,
                                    'user_id' => $user_id,
                                    'action' => 'VIEW',
                                ];
                                $ShareDriveModel->create($param);
                            }
                            continue;
                        }
                    }
                }
            } catch (\Exception $ex) {
                $response = OFFICE_Error::handle($ex);
                return $this->response->errorBadRequest($response['message']);
            }
        return ['status' => 'Share file Thành Công', 'message' => "Share file Thành Công!"];
    }

    public function checkUser($all_user, $inputUser)
    {
        foreach ($inputUser as $user_id) {
            if (in_array($user_id, $all_user)) {
                continue;
            };
            array_push($all_user, $user_id);
        }
        $all_user_id = implode(',', $all_user);
        return $all_user_id;
    }
}
