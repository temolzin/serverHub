<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\PowerLog;

class Server extends Model
{
    use HasFactory;
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

    protected $fillable = [
        'owner_id',
        'uuid',
        'type_application_id',
        'database_id',
        'created_by',
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
        'creation_date',
        'comments',
    ];

    protected static function booted()
    {
        static::creating(function ($server) {
            if (empty($server->uuid)) {
                $server->uuid = (string) Str::uuid();
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function typeApplication()
    {
        return $this->belongsTo(TypeApplication::class);
    }

    public function instances()
    {
        return $this->hasMany(Instance::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
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

    public function stateLabel(): string
    {
        return $this->isPoweredOff() ? 'Apagado' : 'Encendido';
    }

    public function database()
    {
        return $this->belongsTo(Database::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function powerLogs()
    {
        return $this->morphMany(PowerLog::class, 'powerable');
    }
}
