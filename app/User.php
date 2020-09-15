<?php

namespace App;

//use App\Candidate;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
//use Sametsahindogan\JWTRedis\Traits\JWTRedisHasRoles;
//use Spatie\Permission\Traits\HasRoles;
//use Maklad\Permission\Traits\HasRoles;
//use Moloquent;

class User extends Eloquent implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, JWTSubject
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;
    //use HasRoles;
    /**
     * Connection DB
     *
     * @var string mon
     */
    protected $connection = 'mongodb';
    //protected $collection = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'last_login','is_active','role_id','created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        '_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_login' => 'datetime',
    ];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @var integer
     */
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();
        static::saving(function($model) {
            $first_user = self::select('id')->orderBy('id','desc')->first();
            if(!$first_user){
                $model->id=1;
            }
            else{
                $model->id=$first_user->id+1;
            }
        });
    }

    public function candidates(){
        return $this->hasMany(Candidate::class,'owner');
    }
    public function createdBy(){
        return $this->hasMany(Candidate::class,'created_by');
    }

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

    public function assignRole($role="manager")
    {
        $role=\App\Role::where(["name"=>$role])->first();
        if(isset($role)){
            $this->role_id=$role->id;
        }
        else{
            $this->role_id='_';
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'roles','id');
    }

    public static function findByRole(Role $role)
    {
        return $role->users()->get();
    }

    public function hasRole($role)
    {
        $role=\App\Role::where(["name"=>$role])->first();
        return $this->roles()->get()->contains($role);
    }
}
