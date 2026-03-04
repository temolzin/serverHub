<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Database extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'instance_id',
        'owner_id',
        'name',
        'type',
        'status',
        'comments',
        'port',
        'version',
        'last_update',
    ];

    public function instance()
    {
      return $this->belongsTo(Instance::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function servers()
    {
        return $this->hasMany(Server::class);
    }
}
