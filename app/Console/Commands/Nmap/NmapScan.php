<?php

namespace App\Console\Commands\Nmap;

//use Log;
use App\ScanTask;
use App\NmapScanner;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class NmapScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nmap:scan {scanTaskId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to execute nmap with a provided profile';

    /**
     * The Nmap Scan Service
     *
     * @var NmapScanner
     */
    protected $nmapScanner;

    /**
     * Create a new command instance.
     *
     * @param NmapScanner $nmapScanner
     */
    public function __construct(NmapScanner $nmapScanner = null)
    {
        parent::__construct();

        $this->nmapScanner = $nmapScanner ? $nmapScanner : new NmapScanner();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $scanTaskId = filter_var($this->argument('scanTaskId'), FILTER_SANITIZE_NUMBER_INT);


        /**
         * Will find the single scan task in the provided database
         * if the issued task of the user contains more than one scan task
         * each will be handled in a seperate thread
         *
         * @var ScanTask $scanTask
         */

        try {
            $scanTask = ScanTask::findOrFail($scanTaskId);

            if($scanTask) {
                // Retrieve the associated profile via Eloquent's dynamic properties
                $profile = $scanTask->scan_profile;
                $this->nmapScanner->setOptions($profile->command);

                $this->info('Started Scan Task #'.$scanTaskId);

                // Execute the scan.
                $this->nmapScanner->scan($scanTaskId, $scanTask->target);

                $this->info('Finished Scan Task #'.$scanTaskId);

            }
            else {
                $this->info('Scan Task #'.$scanTaskId.' doesn\'t exist');
            }
        } catch (\Exception $e) {
            $scanTask->progress = "error";
            $scanTask->result()->data = json_encode([
                'error' => true,
                'scan_task' => $scanTaskId,
                'exception' => $e,
            ]);
            $this->error('Task handler threw an Exception: '.$e->getMessage());
        }

    }

}
