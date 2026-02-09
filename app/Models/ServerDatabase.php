<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServerDatabase extends Model
{
    use HasFactory;

    protected $table = 'databases';
    public $timestamps = false;

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
