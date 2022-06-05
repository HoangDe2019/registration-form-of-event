<?php


namespace App\V1\CMS\Controllers;


use App\File;
use App\Folder;
use App\LogFileFolder;
use App\OFFICE;
use App\Supports\Log;
use App\Supports\Message;
use App\Supports\OFFICE_Error;
use App\V1\CMS\Models\FilesModel;
use App\V1\CMS\Models\FolderModel;
use App\V1\CMS\Models\LogFileFolderModel;
use App\V1\CMS\Transformers\File\FileLogActionTransformer;
use App\V1\CMS\Transformers\File\FileTransformer;
use App\V1\CMS\Transformers\File\logFileTransformer;
use App\V1\CMS\Validators\FilesCreateValidator;
use App\V1\CMS\Validators\FilesUpdateValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//use phpDocumentor\Reflection\File;

class FilesController extends BaseController
{
    protected  $model;
    public function __construct()
    {
        $this->model = new FilesModel();
    }

    /**
     * @param Request $request
     * @param FileTransformer $fileTransformer
     * @return \Dingo\Api\Http\Response
     */
    public function search(Request $request, FileTransformer $fileTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 100);
        $result = $this->model->search($input, [], $limit);
        return $this->response->paginator($result, $fileTransformer);
    }

    public function detail($id, FileTransformer $fileTransformer)
    {
        try {
            $result = File::find($id);
            if (empty($result)) {
                return ["data" => []];
            } else {
                $logFile = new LogFileFolder();
                $logFile->file_id = $id;
                $logFile->action = "VIEW";
                $logFile->description = OFFICE::getCurrentUserName() . ' vừa xem file ' . $result->file_name;
                $logFile->created_at = date("Y-m-d H:i:s", time());
                $logFile->created_by = OFFICE::getCurrentUserId();
                $logFile->save();
            }
        } catch (\Exception $ex) {
            if (env('APP_ENV') == 'testing') {
                return $this->response->errorBadRequest($ex->getMessage());
            } else {
                return $this->response->errorBadRequest(Message::get("R011"));
            }
        }
        return $this->response->item($result, $fileTransformer);
    }

    public function update(
        $id,
        Request $request,
        FilesUpdateValidator $departmentsUpdateValidator,
        FileTransformer $fileTransformer
    )
    {
        $input = $request->all();
        $input['id'] = $id;
        $departmentsUpdateValidator->validate($input);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            $logFile = new LogFileFolder();
            $logFile->file_id = $id;
            $logFile->action = "UPDATE";
            $logFile->description = OFFICE::getCurrentUserName() . ' vừa sửa file' . $result->file_name;
            $logFile->created_at = date("Y-m-d H:i:s", time());
            $logFile->created_by = OFFICE::getCurrentUserId();
            $logFile->save();
            Log::update('files', $result->file_name);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($result, $fileTransformer);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $result = File::find($id);
            if (empty($result)) {
                return $this->response->errorBadRequest(Message::get("V003", "ID #$id"));
            }
            $logFile = new LogFileFolder();
            $logFile->file_id = $id;
            $logFile->action = "DELETE";
            $logFile->description = OFFICE::getCurrentUserName() . ' vừa xóa file" ' . $result->file_name;
            $logFile->created_at = date("Y-m-d H:i:s", time());
            $logFile->created_by = OFFICE::getCurrentUserId();
            // 1. Delete Department
            $result->delete();
            Log::delete('files', $result->file_name);
            DB::commit();
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return ['status' => Message::get("file.delete-success", $result->code)];
    }

    public function download($id)
    {
        $file = File::find($id);
        if (empty($file)) {
            return ['data' => null];
        }
        $folder_path = object_get($file, 'folder.folder_path');
        if (empty($folder_path)) {
            $folder_path = "uploads";
        }
//        $folder_path  = url('/v0') . "/img/" . $folder_path;
        $fileDownload = !empty($folder_path) ? $folder_path . '/' . $file->file_name : null;
        if (!empty($fileDownload)) {
            return response()->download($fileDownload, "{$file->file_name}");
        } else {
            return response()->json(['data' => "Download Error"]);
        }

    }

    public function upload(FilesCreateValidator $filesCreateValidator, Request $request, FileTransformer $fileTransformer)
    {

        $input = $request->all();
        $filesCreateValidator->validate($input);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileSize = $file->getSize();
            if ($fileSize > 10000000) {
                return $this->response->errorBadRequest(Message::get("file_size_upload_error"));
            }
            $filenameWithExt = $file->getClientOriginalName();
            $fileName = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $fileNameToStore = $this->removeAccented($fileNameToStore);
            $fileNameToStore = str_replace(' ', "", $fileNameToStore);
            $code = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 6);
            if (!empty($input['folder_id'])) {
                $folderId = $input['folder_id'];
                $folder = Folder::model()->where('id', $folderId)->first();
                if (!file_exists($dir = public_path($folder->folder_path))) {
                    mkdir($dir, 0777, true);
                    chmod($dir, 0777);
                }
                $filePath = realpath(public_path($folder->folder_path));

            } else {
                $filePath = realpath(public_path('uploads'));
            }
            $file->move($filePath, $fileNameToStore);
            $param = [
                'code' => $code,
                'file_name' => $fileNameToStore,
                'title' => !empty($input['title']) ? ($input['title']) : $fileName . '.' . $extension,
                'folder_id' => !empty($input['folder_id']) ? $input['folder_id'] : null,
                'extension' => $extension,
                'module_id' => $input['module_id'],
                'size' => $fileSize,
                'version' => 1,
                'is_active' => 1,
            ];
            $fileModle = new FilesModel();
            $fileModle = $fileModle->create($param);
            $logFile = new LogFileFolder();
            $logFile->file_id = $fileModle->id;
            $logFile->action = "UPLOAD";
            $logFile->description = OFFICE::getCurrentUserName() . ' vừa upload file' . $fileModle->title;
            $logFile->created_at = date("Y-m-d H:i:s", time());
            $logFile->created_by = OFFICE::getCurrentUserId();
            $logFile->save();
            Log::upload('files', $fileModle->title);
            return $this->response->item($fileModle, $fileTransformer);
        } else {
            return response()->json(['data' => "Upload Error"]);
        }
    }

    public function moveFile($id, Request $request, FileTransformer $fileTransformer)
    {
        $input = $request->all();
        $input['id'] = $id;
        $files = File::model()->where('id', '=', $id)->first();
        $csvFile = !empty(object_get($files, 'folder.folder_path')) ?
            realpath(public_path(object_get($files, 'folder.folder_path'))) . '/' . $files->file_name
            : realpath(public_path('uploads')) . '/' . $files->file_name;
        if (!empty($input['folder_id'])) {
            $folder = Folder::model()->where('id', '=', $input['folder_id'])->first();
            $moveFile = realpath(public_path($folder->folder_path));
            $moveFile = $moveFile . '/' . $files->file_name;
        } else {
            $moveFile = $csvFile;
        }
        if (copy($csvFile, $moveFile)) {
            unlink($csvFile);
        }
        $logFile = new LogFileFolder();
        $logFile->file_id = $files->id;
        $logFile->action = "MOVE";
        $logFile->description = OFFICE::getCurrentUserName() . ' vừa di chuyển file ' . $files->title;
        $logFile->created_at = date("Y - m - d H:i:s", time());
        $logFile->created_by = OFFICE::getCurrentUserId();
        $logFile->save();
        Log::move('files', $files->title);
        try {
            DB::beginTransaction();
            $result = $this->model->upsert($input);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response['message']);
        }
        return $this->response->item($result, $fileTransformer);
    }

    public function log_file(Request $request, logFileTransformer $logFileTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $this->model = new LogFileFolderModel();
        $result = $this->model->searchHistory($input, [], $limit);
        return $this->response->paginator($result, $logFileTransformer);
    }

    public function fileLogAction(Request $request, FileLogActionTransformer $fileLogActionTransformer)
    {
        $input = $request->all();
        $limit = array_get($input, 'limit', 20);
        $this->model = new LogFileFolderModel();
        $result = $this->model->searchAction($input, [], $limit);
        return $this->response->paginator($result, $fileLogActionTransformer);
    }

    public function removeAccented($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = str_replace(' ', '_', $str);
        return $str;
    }

    public function ZipFile(Request $request)
    {
        $input = $request->all();
        if (empty($input)) {
            return ["data" => []];
        } else
            try {
                $fileTmp1 = public_path('uploads/a.zip');
                $status = $this->createZipFile($input['file_id'], $fileTmp1);
                if (!$status) {
                    return ['status' => 'nén File thất bại', 'message' => "nén File thất bại!"];
                }
                header('Content-disposition: attachment; filename=files.zip');
                header('Content-type: application/zip');
                readfile($fileTmp1);
            } catch (\Exception $ex) {
                $response = OFFICE_Error::handle($ex);
                return $this->response->errorBadRequest($response['message']);
            }
        return ['status' => 'nén File thành công', 'message' => "nén File thành công!"];
    }

    // Zip File
    public function createZipFile($results = array(), $fileTmp1 = '')
    {
        if (file_exists($fileTmp1)) {
            unlink($fileTmp1);
        }
        $result = File::model()->whereIn('id', $results)->get();
        $validFiles = [];
        foreach ($result as $item) {
            if (empty($item->folder_id)) {
                $file = public_path('uploads/' . $item->file_name);
            } else {
                $foler_path = object_get($item, 'folder.folder_path');
                $file = public_path($foler_path . '/' . $item->file_name);
            }
            $validFiles[] = $file;
        }
        if (count($validFiles)) {
            $zip = new \ZipArchive();
            if ($zip->open($fileTmp1, \ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            foreach ($validFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
            return file_exists($fileTmp1);
        } else {
            return false;
        }
    }
}
