<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Download extends Pivot
{
    protected $table = 'downloads';
}