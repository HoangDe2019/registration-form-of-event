<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class IssueHasUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'issue_has_users';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}