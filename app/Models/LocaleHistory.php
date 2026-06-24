<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocaleHistory extends Model
{
    protected $fillable = [
        'locale',
        'ip_address',
        'user_agent'
    ];
}