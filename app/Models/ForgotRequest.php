<?php

namespace App\Models;

use App\Models\core\AcsimaModel;

/**
 * Class ForgotRequest
 * @package App\Models
 * @property integer user_id
 * @property string key
 * @property User user
 */
class ForgotRequest extends AcsimaModel
{
    protected $fillable = ['user_id', 'key'];

    public static function generateKey()
    {
        return hash_hmac('sha256', str_random(28), config('app.key'));
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
