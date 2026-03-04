<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GcpMachine extends Model
{
  use SoftDeletes;

  public const POWERED_OFF_VALUES = [
    '0',
    'false',
    'off',
    'poweredoff',
  ];

  public const POWERED_ON_VALUES = [
    '1',
    'true',
    'on',
    'poweredon',
  ];

  protected $table = 'gcp_machines';

  protected $fillable = [
    'project_name',
    'application_id',
    'uuid',
    'state',
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

  protected static function booted()
  {
    static::creating(function ($machine) {
      if (empty($machine->uuid)) {
        $machine->uuid = (string) Str::uuid();
      }
    });
  }

  public function owner()
  {
    return $this->belongsTo(Owner::class);
  }

  public function application()
  {
    return $this->belongsTo(Application::class);
  }

  public function isPoweredOff(): bool
  {
    $state = strtolower(trim((string) $this->state));

    return in_array($state, self::POWERED_OFF_VALUES, true);
  }

  public function isPoweredOn(): bool
  {
    $state = strtolower(trim((string) $this->state));

    if ($state === '') {
      return true;
    }

    if (in_array($state, self::POWERED_ON_VALUES, true)) {
      return true;
    }

    return !in_array($state, self::POWERED_OFF_VALUES, true);
  }

  public function normalizedState(): string
  {
    return $this->isPoweredOff() ? 'poweredOff' : 'poweredOn';
  }
}
