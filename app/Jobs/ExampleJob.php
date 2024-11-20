<?php
namespace App\Jobs;
use Illuminate\Support\Facades\Log;

class ExampleJob
{
    public function handle($param1, $param2)
    {
        // Job logic here
        Log::info("Job executed with parameters: {$param1}, {$param2}");
    }
}

