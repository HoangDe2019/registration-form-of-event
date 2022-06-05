<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 6/6/2019
 * Time: 10:48 PM
 */

namespace App\V1\CMS\Transformers\File;


use App\LogFileFolder;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class FileLogActionTransformer extends TransformerAbstract
{
    public function transform(LogFileFolder $file)
    {
        try {
            return [
                'id' => $file->id,
                'action' => $file->action,
                'file_id' => $file->file_id,
                'description' => $file->description,
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