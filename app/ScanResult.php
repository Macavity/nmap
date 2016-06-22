<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ScanResult
 * @package App
 *
 * @property integer    $id
 * @property integer    $scan_task_id
 * @property string     $progress - Progress string that will indicate the status to the user
 * @property string     $data -
 */
class ScanResult extends Model
{
    protected $fillable = ['scan_task_id', 'progress', 'data'];

    public function task()
    {
        $this->belongsTo(ScanTask::class);
    }

    public function properties()
    {
        $this->hasMany(ScanResultProperty::class);
    }
}
