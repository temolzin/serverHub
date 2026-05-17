<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Owner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'number_phone',
        'created_by'
    ];

    public function gcpMachines()
    {
        return $this->hasMany(GcpMachine::class);
    }

    public function databases()
    {
        return $this->hasMany(Database::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsInUseAttribute()
    {
        return $this->gcpMachines()->exists() || $this->databases()->exists();
    }
}
