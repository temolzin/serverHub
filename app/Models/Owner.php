<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'last_name',
    'email',
    'number_phone',
  ];
}
