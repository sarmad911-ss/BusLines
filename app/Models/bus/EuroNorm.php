<?php

namespace App\Models;

use App\Models\core\AcsimaModel;

/**
 * Class EuroNorm
 * @package App\Models
 * @property integer id
 * @property string name
 */

class EuroNorm extends AcsimaModel
{
    protected $fillable = ['name'];
}
