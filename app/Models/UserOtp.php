<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserOtp extends Model
{
    protected $fillable = ['user_id', 'otp', 'expires_at'];

    public function isExpired()
    {
        return Carbon::now()->gt($this->expires_at); 
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
