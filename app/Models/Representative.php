<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    protected $fillable = ['id','user_id','name','phone','email','misc'];
    protected $table = 'client_representatives';
}
