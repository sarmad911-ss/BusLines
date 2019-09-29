<?php

namespace App\Models;

use App\Models\core\AcsimaModel;

/**
 * Class BusType
 * @package App\Models
 * @property integer id
 * @property string name
 */

class BusType extends AcsimaModel
{
    protected $fillable = ['name'];
}
