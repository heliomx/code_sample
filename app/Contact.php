<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    const STATUS_NEW = 'N';
    const STATUS_RESOLVED = 'R';

    protected $fillable = [
        'updated_at', 'created_at', 'name', 'subject',  'message', 'email', 'status', 'annotations'
    ];

    protected $dates = [ 'updated_at', 'created_at' ];



}