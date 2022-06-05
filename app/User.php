<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden
        = [
            'password',
        ];

    protected $fillable = [
        'username',
        'phone',
        'code',
        'department_id',
        // 'company_id',
        // 'company_code',
        // 'company_name',
        'password',
        'email',
        'verify_code',
        'expired_code',
        'role_id',
        'note',
        'price_show',
        'is_active',
        'deleted',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function profile()
    {
        return $this->hasOne(__NAMESPACE__ . '\Profile', 'user_id', 'id');
    }

    public function schedule()
    {
        return $this->hasMany(__NAMESPACE__ . '\MedicalSchedule', 'user_id', 'id');
    }

    public function role()
    {
        return $this->hasOne(__NAMESPACE__ . '\Role', 'id', 'role_id');
    }

    public function department()
    {
        return $this->hasOne(__NAMESPACE__ . '\Department', 'id', 'department_id');
    }
    public function health_record_books()
    {
        return $this->hasOne(__NAMESPACE__ . '\HealthBookRecord', 'user_id', 'id');
    }

    public function company()
    {
        return $this->hasOne(__NAMESPACE__ . '\Company', 'id', 'company_id');
    }

    public function bookBefore()
    {
        return $this->hasMany(__NAMESPACE__ . '\BookBefore', 'user_id', 'id');
    }


    public function education()
    {
        return $this->hasMany(__NAMESPACE__ . '\Education', 'user_id', 'id');
    }

    public function medicalhistory()
    {
        return $this->hasMany(__NAMESPACE__ . '\MedicalHistory', 'user_id', 'id');
    }
    public static final function model()
    {
        $classStr = get_called_class();
        /** @var Model $class */
        $class = new $classStr();
        return $class::whereNull($class->getTable() . '.deleted_at');
    }
}
