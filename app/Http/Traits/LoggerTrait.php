<?php

namespace App\Http\Traits;
use App\Logger;

trait LoggerTrait
{
    //

    public function log($type, $description)
    {
        $logger = new Logger;
        $logger->log_type = $type;
        $logger->description = $description;
        $logger->save();
    }

    public function viewlogs()
    {
        $logs = Logger::all()->get();
        return view('admin.logs')->with(['logs' => $logs]);
    }
}
