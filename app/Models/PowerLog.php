<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PowerLog extends Model
{
    protected $fillable = [
        'action',
        'motive',
        'created_by',
    ];

    public function powerable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute()
    {
        return class_basename($this->powerable_type);
    }
}
