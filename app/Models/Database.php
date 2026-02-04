<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
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
