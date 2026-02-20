<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'server_id',
        'memory',
        'version',
        'edition',
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
}
