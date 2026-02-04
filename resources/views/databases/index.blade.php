@extends('layouts/contentNavbarLayout')

@section('title', 'Databases')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Databases</h5>
        </div>

        <div class="table-responsive text-nowrap">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Server</th>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Port</th>
                <th>Version</th>
                <th>Last Update</th>
                <th>Comments</th>
              </tr>
            </thead>

            <tbody>
              @forelse ($databases as $database)
                <tr>
                  <td>{{ $database->id }}</td>

                  <td class="fw-medium">
                    {{ $database->server->dns_name }}
                  </td>

                  <td>{{ $database->name }}</td>
                  <td>{{ $database->type }}</td>

                  <td>
                    <span class="badge bg-label-success">
                      {{ $database->status }}
                    </span>
                  </td>

                  <td>{{ $database->port }}</td>
                  <td>{{ $database->version }}</td>
                  <td>{{ $database->last_update ?? '—' }}</td>

                  <td class="small text-muted">
                    {{ $database->comments ?? '—' }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center text-muted">
                    No databases found
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
