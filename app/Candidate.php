<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Candidate extends Eloquent
{
    //
    /**
     * @var integer
     */
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();
        static::saving(function($model) {
            //$model->id = self::getID();
            $first_user = self::select('id')->orderBy('id','desc')->first();
            if(!$first_user){
                $model->id=1;
            }
            else{
                $model->id=$first_user->id+1;
            }
        });
    }
    public function owner(){
        return $this->belongsTo(User::class,'created_by');
    }
}
