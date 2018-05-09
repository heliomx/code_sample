<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramFile extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'file_name', 'program_number', 'publish_on', 'updated_at', 'created_at' ];
    protected $dates = ['publish_on', 'created_at', 'updated_at'];
    
}
