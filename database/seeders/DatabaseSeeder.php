<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Database;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {

    if (!$server) {
      return;
    }

    Database::create([
      'server_id'   => $server->id,
      'name'        => 'serverhub_db',
      'type'        => 'MySQL',
      'status'      => 'active',
      'comments'    => 'Main production database',
      'port'        => 3306,
      'version'     => '8.0',
      'last_update' => now(),
    ]);
  }
}
