<?php


namespace App\V1\CMS\Validators;


use App\File;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use Illuminate\Http\Request;

class FilesUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'required|exists:files,id,deleted_at,NULL',
            'folder_id' => 'nullable|exists:folders,id,deleted_at,NULL',
        ];
    }

    protected function attributes()
    {
        return [
            'folder_id' => Message::get("folder_id"),
        ];
    }
}