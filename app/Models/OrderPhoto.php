<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPhoto extends Model
{
    protected $fillable = ['order_id', 'path', 'caption'];
}
