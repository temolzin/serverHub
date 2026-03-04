<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Application extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'owner_id',
    'server_id',
    'name',
    'version',
    'status',
    'type',
    'comments',
    'processes',
    'assigned_memory',
    'installation_route',
    'latest_security_patch',
    'user_service',
    'cron_jobs',
    'created_by'
  ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function gcpMachines()
    {
        return $this->hasMany(GcpMachine::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
