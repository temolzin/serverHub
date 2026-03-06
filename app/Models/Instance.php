<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Instance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'server_id',
        'memory',
        'version',
        'edition',
        'created_by'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function databases()
    {
        return $this->hasMany(Database::class);
    }

    public function getServerHostnameAttribute()
    {
        return $this->server?->hostname_internal;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
