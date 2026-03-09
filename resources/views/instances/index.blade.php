@extends('layouts/contentNavbarLayout')

@section('title', 'Instancias')
@section('content')
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">Instancias</h5>
                <div class="ms-auto d-flex gap-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createInstanceModal">
                    <i class="bx bx-plus me-1"></i> Agregar Instancia
                    </button>
                    <a href="{{ route('export', 'instances') }}" class="btn btn-primary">Exportar Excel</a>
                </div>
            </div>
            <div class="card-body">
            <div>
                <table id="dt-instances" class="table align-middle" style="width:100%">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Servidor</th>
                        <th>Memoria</th>
                        <th>Version</th>
                        <th>Edicion</th>
                        <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('instances.search', ['instances' => $instances, 'servers' => $servers])
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>
    @include('instances.create')
@endsection

@push('scripts')
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Listo!', text: @json(session('success')), confirmButtonText: 'Perfecto', timer: 5000, timerProgressBar: true });
        });
    </script>
    @endif
    <script>
        jQuery(function($) {
            $('#dt-instances').DataTable({
                pageLength: 10, deferRender: true,
                dom: '<"dt-top d-flex justify-content-between align-items-center gap-3 mb-2"lf>rt<"dt-bottom d-flex justify-content-end align-items-center mt-2"p>',
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, searchable: false, targets: -1 }],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json', paginate: { previous: '&#8249;', next: '&#8250;' }
                }
            });
        });
    </script>
@endpush
