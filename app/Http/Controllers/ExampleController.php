<?php

namespace App\Http\Controllers;

use App\Jobs\ExampleJob;

class ExampleController extends Controller
{
    public function triggerJob()
    {
        // Create a new model instance (this could be fetched from the database)

        // Run the job in the background
        runBackgroundJob(ExampleJob::class, 'handle', ['param1Value', 'param2Value']);

        return response()->json(['message' => 'Job is running in the background.']);
    }
}


