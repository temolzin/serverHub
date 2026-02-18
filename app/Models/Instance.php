<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
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
}
