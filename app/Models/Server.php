<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $fillable = [
    'owner_id',
    'uuid',
    'type_application_id',
    'vm_according_to_the_vmware',
    'state',
    'dns_name',
    'primary_ip_address',
    'environment',
    'datacenter',
    'os_according_to_the_vmware',
    'os_version_internal',
    'hostname_internal',
    'ip_user',
    'ip_monitoring',
    'other_ips',
    'ram_memory',
    'swap_memory',
    'latest_security_patch',
    'comments',
  ];

  public function owner()
  {
    return $this->belongsTo(Owner::class);
  }

  public function typeApplication()
  {
    return $this->belongsTo(TypeApplication::class);
  }

  public function databases()
  {
    return $this->hasMany(Database::class);
  }

  public function instances()
  {
    return $this->hasMany(Instance::class);
  }
}
