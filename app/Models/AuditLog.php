<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public static bool $suppressed = false;

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

    private function decodeJsonSafely($data)
    {
        if (is_array($data)) {
            return $data;
        }

        if (is_string($data)) {
            $decoded = json_decode($data, true);

            if (is_string($decoded)) {
                return json_decode($decoded, true);
            }

            return $decoded;
        }

        return $data;
    }

    public function getBeforePrettyAttribute()
    {
        $data = $this->decodeJsonSafely($this->before_data);

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function getAfterPrettyAttribute()
    {
        $data = $this->decodeJsonSafely($this->current_data);

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
