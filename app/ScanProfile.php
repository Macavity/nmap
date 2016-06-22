<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ScanProfile
 * Scan profile that will run scans based on $command
 * A potential alternative would be to make a single class for every profile which contains the special options
 * of this profile. Both approaches have their advantages.
 *
 * @package App
 *
 * @property integer    $id
 * @property string     $label - Identifier string, could be localized
 * @property string     $description - Description text
 * @property string     $command - command parameters that will be executed
 */
class ScanProfile extends Model
{

    // Potential extension to give the scan profiles additional options
    // these should be in another table to keep the schema scalable
    /*public function properties()
    {
        $this->hasMany(ScanResultProperty::class);
    }*/
}
