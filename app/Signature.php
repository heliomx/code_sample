<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $table = 'signatures';
    protected $dates = ['created_at', 'updated_at'];

    public function downloads()
    {
        return $this->hasMany('App/Download');
    }
}