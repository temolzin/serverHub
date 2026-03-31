<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Server;

class TypeApplication extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'type_application',
    'name_application',
    'created_by'
  ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function servers()
    {
        return $this->hasMany(Server::class, 'type_application_id');
    }

    public function getTypeLabelAttribute()
    {
        return match(strtoupper($this->type_application)) {
            'WEB' => 'Web',
            'API' => 'API',
            'WORKER' => 'Worker',
            'UNKNOWN' => 'Desconocido',
            default => ucfirst($this->type_application),
        };
    }
}
