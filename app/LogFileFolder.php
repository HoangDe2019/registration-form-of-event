<?php


namespace App;


class LogFileFolder extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'log_files';

    /**
     * @var array
     */
    protected $fillable = [
        'action',
        'description',
        'file_id',
        'deleted',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];

    public function files()
    {
        return $this->hasOne(__NAMESPACE__ . '\Files', 'id', 'file_id');
    }

    public function createdBy()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'created_by');
    }

}