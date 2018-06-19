<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{

    protected $casts = [
        'doc' => 'array',
    ];

    protected $fillable = [
        'updated_at', 'created_at', 'doc', 'doc_type'
    ];

    protected $dates = [ 'updated_at', 'created_at' ];



}