@extends('layouts/contentNavbarLayout')

@section('title', 'Historial de cambios')

@section('content')

  <style>
    .text-purple {
      color: #696cff;
    }

    .btn-purple {
      background: rgba(105, 108, 255, 0.1);
      color: #696cff;
      border: none;
      transition: 0.2s;
    }

    .btn-purple:hover {
      background: #696cff;
      color: #fff;
    }

    .badge-update {
      background-color: #ff9f43;
      color: #fff;
    }

    .badge-delete {
      background-color: #ea5455;
      color: #fff;
    }

    .json-box {
      background: #f8f9fa;
      padding: 15px;
      margin: 0;
      font-size: 13px;
      font-family: "Courier New", monospace;
      max-height: 500px;
      overflow: auto;
      border-radius: 0 0 10px 10px;
      white-space: pre;
    }

    .json-before {
      border-top: 4px solid #dc3545;
    }

    .json-after {
      border-top: 4px solid #28c76f;
    }

    .bg-danger-soft {
      background: rgba(220, 53, 69, 0.12);
      color: #dc3545;
    }

    .bg-success-soft {
      background: rgba(40, 199, 111, 0.12);
      color: #28c76f;
    }
  </style>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">
        <i class="bx bx-history text-purple me-2"></i>
        Historial de cambios
      </h5>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Módulo</th>
            <th>Acción</th>
            <th>Registro</th>
            <th>Fecha</th>
            <th class="text-center">Ver</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($logs as $log)
            <tr>
              <td>
                <strong>{{ $log->user->name ?? 'Usuario eliminado' }}</strong><br>
                <small class="text-muted">ID: {{ $log->alter_by }}</small>
              </td>
              <td>
                <span class="fw-semibold text-uppercase">
                  {{ $log->module_label }}
                </span>
              </td>
              <td>
                @if ($log->action === 'create')
                  <span class="badge badge-create">CREACIÓN</span>
                @elseif ($log->action === 'update')
                  <span class="badge badge-update">ACTUALIZACIÓN</span>
                @elseif ($log->action === 'delete')
                  <span class="badge badge-delete">ELIMINACIÓN</span>
                @endif
              </td>
              <td>#{{ $log->record_id }}</td>
              <td>
                {{ $log->created_at->format('d/m/Y') }}<br>
                <small class="text-muted">
                  {{ $log->created_at->format('h:i A') }}
                </small>
              </td>
              <td class="text-center">
                <button class="btn btn-sm btn-icon btn-purple" data-bs-toggle="modal"
                  data-bs-target="#logModal{{ $log->id }}">
                  <i class="bx bx-show"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">Sin registros</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @foreach ($logs as $log)
    <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-purple">
              <i class="bx bx-detail"></i>Detalle del cambio
            </h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="card shadow-sm">
                  <div class="card-header bg-danger-soft fw-bold">
                    Antes
                  </div>
                  <pre class="json-box json-before"> {{ $log->before_pretty }} </pre>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card shadow-sm">
                  <div class="card-header bg-success-soft fw-bold">
                    Después
                  </div>
                  <pre class="json-box json-after"> {{ $log->after_pretty }} </pre>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach

@endsection
