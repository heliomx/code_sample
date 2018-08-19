<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Signature extends Pivot
{
    protected $table = 'signatures';
    protected $dates = ['created_at', 'updated_at'];

}