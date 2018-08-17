<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $table = 'downloads';
    public $timestamps = false;
    protected $fillable = [ 'program_file_id', 'client_id', 'download_date' ];

    protected $dates = [ 'download_date' ];

    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($query) {
            $query->download_date = new Carbon();
        });

    }

    public function client(){
        return $this->belongsTo('App\Client');
    }

    public function programFile(){
        return $this->belongsTo('App\ProgramFile');
    }
}