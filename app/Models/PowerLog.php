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
        return match (class_basename($this->powerable_type)) {
            'Server' => 'On-Premise',
            'GcpMachine' => 'GCP Machine',
            default => 'N/A',
        };
    }

    public function getResourceNameAttribute()
    {
        $baseType = class_basename($this->powerable_type);

        return match ($baseType) {
            'Server' => optional($this->powerable)->hostname_internal ?? 'N/A',
            'GcpMachine' => optional($this->powerable)->machine_name ?? 'N/A',
            default => 'N/A',
        };
    }

    public function getResourceIdAttribute()
    {
        return optional($this->powerable)->id ?? 'N/A';
    }
}
