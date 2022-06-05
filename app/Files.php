<?php


namespace App;


class Files extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'files';

    protected $fillable = [
        'code',
        "file_name",
        "title",
        "folder_id",
        "module_id",
        "share_user_id",
        'extension',
        'size',
        'version',
        "is_active",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

    public function folder()
    {
        return $this->hasOne(__NAMESPACE__ . '\Folder', 'id', 'folder_id');
    }

    public function createdBy()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'created_by');
    }

    public function share_file()
    {
        return $this->hasOne(__NAMESPACE__ . '\ShareDrive', 'file_id', 'id');
    }
}