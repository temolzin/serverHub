<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('owners', function (Blueprint $table) {
  $table->id();
  $table->string('name');
  $table->string('last_name');
  $table->string('email')->unique();
  $table->string('number_phone')->nullable();
  $table->timestamps();
  $table->softDeletes();
});
