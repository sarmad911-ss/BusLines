<?php

namespace App\Models\trip;

use Illuminate\Database\Eloquent\Model;

class TripStatus extends Model
{
    protected $fillable = ['name'];

    const DRAFT = 1;
    const PLANNED = 2;
    const APPROVED = 3;
    const ENDED = 4;
    const CANCELED = 5;
}
