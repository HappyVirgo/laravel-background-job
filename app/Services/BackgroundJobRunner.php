<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class BackgroundJobRunner
{
    /**
     * Run a job in the background.
     *
     * @param string $className The class to instantiate.
     * @param string $method The method to invoke.
     * @param array $parameters Parameters to pass to the method.
     * @param int $maxRetries Number of retries if the job fails.
     * @param int $delay Retry delay in seconds.
     */
    public function runJob(string $className, string $method, array $parameters = [], int $maxRetries = 3, int $delay = 5)
    {
        // Sanitize class and method name
        if (!$this->isValidJob($className, $method)) {
            Log::channel('background_jobs')->error("Invalid class or method: {$className}::{$method}");
            return;
        }

        // Background process identifier
        $logMessage = "Running job: {$className}::{$method} with parameters: " . json_encode($parameters);
        Log::channel('background_jobs')->info($logMessage);

        $retries = 0;
        $success = false;

        while ($retries < $maxRetries && !$success) {
            try {
                $retries++;
                $this->executeJob($className, $method, $parameters);
                Log::channel('background_jobs')->info("Job completed successfully: {$className}::{$method}");
                $success = true;
            } catch (Exception $e) {
                Log::channel('background_jobs')->error("Error in job: {$className}::{$method}. Attempt {$retries} failed. Error: {$e->getMessage()}");
                if ($retries < $maxRetries) {
                    sleep($delay);
                }
            }
        }

        if (!$success) {
            Log::channel('background_jobs')->error("Job failed after {$maxRetries} attempts: {$className}::{$method}");
        }
    }

    /**
     * Validate class and method names to avoid unauthorized or harmful code.
     *
     * @param string $className
     * @param string $method
     * @return bool
     */
    private function isValidJob(string $className, string $methodName): bool
    {
        // Define a list of allowed classes and methods
        $allowedJobs = [
            'App\\Jobs\\ExampleJob' => ['handle'],
        ];
    
        return isset($allowedJobs[$className]) && in_array($methodName, $allowedJobs[$className]);
    }

    /**
     * Execute the job by instantiating the class and calling the method.
     *
     * @param string $className
     * @param string $method
     * @param array $parameters
     * @throws Exception
     */
    private function executeJob(string $className, string $method, array $parameters)
    {
        if (!class_exists($className)) {
            throw new Exception("Class {$className} does not exist.");
        }

        $object = new $className();
        if (!method_exists($object, $method)) {
            throw new Exception("Method {$method} does not exist in class {$className}.");
        }

        call_user_func_array([$object, $method], $parameters);
    }
}
