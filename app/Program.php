<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [ 'name' ];

    public function signatures(){
        return $this->hasMany('App\Signature');
    }

    public function clients(){
        return $this->belongsToMany('App\Client', 'App\Signature');
    }
}
