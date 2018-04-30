<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [ 'name', 'description', 'program_type', 'img', 'updated_at', 'created_at' ];
    protected $appends = ['qt_signatures', 'full_img_path'];

    public function signatures(){
        return $this->hasMany('App\Signature');
    }

    public function clients(){
        return $this->belongsToMany('App\Client', 'App\Signature');
    }

    public function getQtSignaturesAttribute() {
        return $this->signatures()->count();
    }

    public function getFullImgPathAttribute() {
        $pattern = "/public\//";

        return asset(\preg_replace($pattern, 'storage/', $this->img));
    }
}
