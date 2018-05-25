<?php

namespace App\Listeners;

use \OneOffTech\TusUpload\Events\TusUploadCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Package;
use Log;

class UploadCompleted
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TusUploadCompleted  $event
     * @return void
     */
    public function handle(TusUploadCompleted $event)
    {
        Log::info('AQUI ' . $event->upload->path());
        Package::open($event->upload);
    }
}
