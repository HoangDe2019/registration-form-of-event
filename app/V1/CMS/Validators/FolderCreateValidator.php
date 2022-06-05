<?php


namespace App\V1\CMS\Validators;


use App\Folder;
use App\Http\Validators\ValidatorBase;
use App\Supports\Message;

class FolderCreateValidator extends ValidatorBase
{
    protected function rules()
    {
        return [
            'folder_name' => 'required|max:50',
            'module_id' => 'required|exists:modules,id,deleted_at,NULL',
            'folder_path' => [
                'nullable',
                'max:200',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $result = Folder::model()->where('folder_path', $value)->first();
                        if (empty($result)) {
                            return $fail(Message::get("path", "$attribute: #$value"));
                        }
                    }
                    return true;
                }
            ],
        ];
    }

    protected function attributes()
    {
        return [
            'folder_name' => Message::get("folder_name"),
            'module_id' => Message::get("module_id"),
        ];
    }
}