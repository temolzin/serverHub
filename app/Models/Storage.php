<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'datacenter',
        'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
