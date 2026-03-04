<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

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
}
