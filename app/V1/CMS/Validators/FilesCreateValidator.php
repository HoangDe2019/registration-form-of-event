<?php


namespace App\V1\CMS\Validators;


use App\Http\Validators\ValidatorBase;
use App\Supports\Message;


class FilesCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'folder_id' => 'nullable|exists:folders,id,deleted_at,NULL',
            'module_id' => 'required|exists:modules,id,deleted_at,NULL',
        ];
    }

    protected function attributes()
    {
        return [
            'folder_id' => Message::get("folder_id"),
            'module_id' => Message::get("module_id"),
        ];
    }
}