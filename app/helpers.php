<?php

use App\Services\BackgroundJobRunner;

/**
 * Run a background job asynchronously.
 *
 * @param string $className
 * @param string $method
 * @param array $parameters
 * @param int $maxRetries
 * @param int $delay
 */
if (!function_exists('runBackgroundJob')) {
    function runBackgroundJob(string $className, string $method, array $parameters = [], int $maxRetries = 3, int $delay = 5)
    {
        $jobRunner = app(BackgroundJobRunner::class);
        $jobRunner->runJob($className, $method, $parameters, $maxRetries, $delay);
    }
}
