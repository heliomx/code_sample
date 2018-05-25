<?php

namespace App\Lib;
use App\ProgramFile;
use Carbon\Carbon;

class ProgramPublication {
    
    public static function check()
    {
        // validates and manage current program publication
        self::unpublish();
        // verifies if there's programs for publication
        self::publish();
    }

    private static function unpublish()
    {
        $now = Carbon::now();
        $programFiles = ProgramFile::whereStatus( ProgramFile::STATUS_PUBLISHED )
            ->where('publish_end', '<', $now )
            ->get();
            
        foreach( $programFiles as $pfile )
        {
            $pfile->unpublish();
        }
            
    }

    private static function publish()
    {
        $now = Carbon::now();

        ProgramFile::whereStatus( ProgramFile::STATUS_WAITING )
            ->where('publish_start', '<=', $now )
            ->where('publish_end', '>', $now )
            ->update(['status' => ProgramFile::STATUS_PUBLISHED]);
    }
}