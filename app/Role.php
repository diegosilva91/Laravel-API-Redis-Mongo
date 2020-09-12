<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
//use Moloquent;

class Role extends Eloquent
{
    //
    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role'
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
}
