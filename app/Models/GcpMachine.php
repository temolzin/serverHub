<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GcpMachine extends Model
{
  use SoftDeletes;

  protected $table = 'gcp_machines';

  protected $fillable = [
    'project_name',
    'environment',
    'machine_name',
    'machine_internal_name',
    'operations_system',
    'latest_security_patch',
    'internal_ip',
    'alias_ip',
    'alias2_ip',
    'alias3_ip',
    'other_ips',
    'kernel_version',
    'ram_memory',
    'swap_memory',
    'owner_id',
  ];
  public function owner()
  {
    return $this->belongsTo(Owner::class);
  }
}
