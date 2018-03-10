<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $fillable = [
        'device_name', 'IP'
    ];
}
