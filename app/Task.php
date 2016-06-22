<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @package App
 *
 * @property integer    $user_id - The user this task belongs to
 * @property integer    $recurring_timespan - (optional) the timespan after creation when this task should be repeated (could be an integer, could be a string like "monthly")
 * @property \DateTime  $scheduled_from - First time the task should run
 * @property \DateTime  $scheduled_to - Last time the task should run
 * @property \DateTime  $created_at
 * @property \DateTime  $modified_at
 */
class Task extends Model
{
    //
}
