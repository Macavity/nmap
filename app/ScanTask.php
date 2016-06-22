<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

/**
 * Class ScanTask
 * @package App
 *
 * @property integer    $id
 * @property integer    $task_id - The task if this scan task belongs to
 * @property integer    $scan_profile_id - The scan profile preset id this scan tasks instantiates
 * @property ScanProfile $scan_profile - Eloquent's dynamic property
 * @property string     $target - The target host, needs to be sanitized before inserted in the database of course
 * @property string     $progress - the status indicator the task got from the scan service
 */
class ScanTask extends Model
{
    /**
     * @return ScanResult
     */
    public function result()
    {
        return $this->hasOne(ScanResult::class);
    }

    /**
     * @return ScanProfile
     */
    public function scan_profile()
    {
        return $this->hasOne(ScanProfile::class, 'id');
    }

}
