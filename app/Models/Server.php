<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ServerDatabase;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function databases()
    {
        return $this->hasMany(ServerDatabase::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function typeApplication()
    {
        return $this->belongsTo(TypeApplication::class);
    }
}
