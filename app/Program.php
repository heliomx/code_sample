<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'name', 'description', 'program_type', 'img', 'updated_at', 'created_at' ];
    protected $appends = ['qt_signatures', 'full_img_path'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    
    public function signatures(){
        return $this->hasMany('App\Signature');
    }

    public function clients(){
        return $this->belongsToMany('App\Client', 'App\Signature');
    }

    public function getQtSignaturesAttribute() {
        return $this->signatures()->whereStatus('A')->count();
    }

    public function files(){
        return $this->hasMany('App\ProgramFile');
    }

    public function getFullImgPathAttribute() {
        $pattern = "/public\//";

        return asset(\preg_replace($pattern, 'storage/', $this->img));
    }
}
