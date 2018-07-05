<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramFile extends Model
{
    const STATUS_WAITING = 'W';
    const STATUS_PUBLISHED = 'P';
    const STATUS_UNPUBLISHED = 'U';

    protected $fillable = [ 'file_name', 'program_number', 'program_id', 'package_id', 'publish_on', 'updated_at', 'created_at' ];
    protected $dates = ['publish_start', 'publish_end', 'created_at', 'updated_at', 'air_on'];
    
    public function program(){
        return $this->belongsTo('App\Program');
    }

    public function package(){
        return $this->belongsTo('App\Package');
    }

    public function unpublish(){
        $this->status = self::STATUS_UNPUBLISHED;
        $this->save();
        if(file_exists ( storage_path("app/packages/$this->file_name") ))
        {
            unlink(storage_path("app/packages/$this->file_name"));
        }
        
    }

    
}
