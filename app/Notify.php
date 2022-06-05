<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 6/25/2019
 * Time: 11:19 PM
 */

namespace App;


class Notify extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifies';

    protected $fillable = [
        "title",
        "issue_id",
        "discuss_id",
        "discuss_issue_id",
        "description",
        "date",
        "sender",
        "receiver",
        "is_active",
        "deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

    public function userSender()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'sender');
    }

    public function userReceiver()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'receiver');
    }

    public function created_By()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'created_by');
    }

    public function updated_By()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'updated_by');
    }

    public function issueNotify()
    {
        return $this->hasOne(__NAMESPACE__ . '\Issue', 'id', 'issue_id');
    }

    public function discussNotify()
    {
        return $this->hasOne(__NAMESPACE__ . '\Discuss', 'id', 'discuss_id');
    }
}