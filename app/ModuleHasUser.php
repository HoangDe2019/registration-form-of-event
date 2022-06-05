<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class ModuleHasUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'module_has_users';
    /**
     * @var string[]
     */
    protected $fillable = [
        'module_id',
        'user_id'
    ];

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}