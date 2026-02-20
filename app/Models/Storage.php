<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
  protected $fillable = [
        'hostname',
        'data_ip',
        'platform',
        'os_name',
        'os_internal',
        'operations_system',
        'internal_ip',
        'environment',
        'datacenter'
    ];
}
