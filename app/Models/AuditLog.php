<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'alter_by',
        'module',
        'action',
        'record_id',
        'before_data',
        'current_data',
    ];

    protected $casts = [
        'before_data' => 'array',
        'current_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'alter_by');
    }

    public function getModuleLabelAttribute()
    {
        $map = [
            'server' => 'On-premise',
            'gcp_machine' => 'Gcp Machine',
            'application' => 'Aplicación',
            'applications' => 'Aplicación',
            'database' => 'Bases de datos',
        ];

        return $map[$this->module] ?? ucfirst(str_replace('_', ' ', $this->module));
    }

    public function getBeforePrettyAttribute()
    {
        return json_encode($this->before_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function getAfterPrettyAttribute()
    {
        return json_encode($this->current_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
