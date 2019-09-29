<?php

namespace App\Models;

use App\Models\core\AcsimaModel;

/**
 * Class UserRole
 * @package App\Models
 * @property string name
 */
class UserRole extends AcsimaModel
{
    const ADMIN = 1;
    const EMPLOYEE = 2;
    const CLIENT = 3;
    const FRIEND = 4;

    protected $fillable = ['name'];
}
