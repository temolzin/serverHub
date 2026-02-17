<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Database extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'server_id',
        'name',
        'type',
        'status',
        'comments',
        'port',
        'version',
        'last_update',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
