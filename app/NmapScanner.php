<?php

namespace App;

use Log;
use Psy\Exception\ParseErrorException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class NmapScanner
 *
 * @package App
 */
class NmapScanner
{
    private $target = "";

    private $ports = [];

    private $logFile;

    /**
     * Potentially set to use another
     * @var string
     */
    private $executable = "nmap";
    private $options;

    public function __construct($executable = 'nmap')
    {
        $this->executable = $executable;
        try {
            $this->testExecution();
        } catch(ProcessFailedException $e) {
            Log::debug("ProcessFailedException in NmapScanner constructor.");
            // TODO Add response the user interface can receive so the user knows the task is delayed
        }
    }

    /**
     * @param $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function scan($scanTaskId, $target)
    {
        try {
            $this->logFile = $this->getLogFilePath($scanTaskId);
            $code = $this->execute($this->options, $target, $scanTaskId);

        } catch (ProcessFailedException $e) {
            Log::debug("ProcessFailedException in NmapScanner scan function.");
            // TODO Add response the user interface can receive so the user knows the task is delayed
        }
    }

    public function testExecution()
    {
        $process = new Process($this->executable.' -h');
        $process->start();
        if($process->isSuccessful()) {
            return $process->getExitCode();
        }
        else {
            throw new ProcessFailedException($process);
        }

    }

    public function execute($command, $target, $scanTaskId)
    {
        $process = new Process($this->executable.' '.$command.' '.$target.' > '.$this->logFile);

        // Hopefully an hour should be enough..(?)
        $process->setTimeout(3600);

        // start an asynchronous process
        $process->start();

        $result = new ScanResult([
            'scan_task_id' => $scanTaskId,
            'progress' => '0%',
        ]);
        $result->save();

        // wait() is blocking
        $process->wait(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo 'OUT > '.$buffer;
            }
        });

        //file_put_contents($this->logFile, $process->getOutput());

        if($process->isSuccessful()) {

            $result->progress = 'done';
            $result->save();

            return $process->getExitCode();
        }
        else {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * Example of how to retrieve the progress of a running scan task
     * @param $scanTaskId
     * @return string
     */
    public function getProgress($scanTaskId)
    {
        $scanFile = $this->getLogFilePath($scanTaskId);
        $content = file_get_contents($scanFile);

        if(substr_count($content, 'Nmap done')) {
            return 'done';
        }
        // not sure about all different possibilities, this is simply an example
        elseif(substr_count($content, 'Connect Scan Timing')) {
            if(preg_match_all("/About (?<percentage>[\\d\\.]+)\\% done/", $content, $matches)) {
                $percentage = array_pop($matches['percentage']);
                return 'connect scan: '.$percentage;
            }
        }
        return 'started';

    }

    private function getLogFilePath($scanTaskId)
    {
        return storage_path() . '/scans/' . $scanTaskId . '.xml';
    }
}