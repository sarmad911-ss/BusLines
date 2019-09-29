<?php

namespace App\Models\trip;

use Illuminate\Database\Eloquent\Model;

class TripType extends Model
{
    protected $fillable = ['name'];

    const TOUR = 1;
    const TRANSFER = 2;
}
