<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeApplication extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'type_application',
    'name_application',
  ];
}
