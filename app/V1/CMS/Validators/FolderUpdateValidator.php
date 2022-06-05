<?php


namespace App\V1\CMS\Validators;


use App\Folder;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;
use Illuminate\Http\Request;

class FolderUpdateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'id' => 'required|exists:folders,id,deleted_at,NULL',
            'module_id' => 'required|exists:modules,id,deleted_at,NULL',
        ];
    }

    protected function attributes()
    {
        return [
            'id' => Message::get("id"),
            'module_id' => Message::get("module_id"),
        ];
    }
}