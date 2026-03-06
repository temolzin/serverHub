@extends('layouts/contentNavbarLayout')

@section('title', 'Propietarios')

@section('content')
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Propietarios</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createOwnerModal">
                <i class="bx bx-plus me-1"></i> Agregar propietario
                </button>
                <a href="{{ route('export', 'owners') }}" class="btn btn-primary">Exportar Excel</a>
            </div>
            </div>
            <div class="card-body">
            <div>
                <table id="dt-owners" class="table align-middle" style="width:100%">
                <thead>
                    <tr>
                    <th>ID</th>
                    <th>Propietario</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @include('owners.search', ['owners' => $owners])
                </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>
    @include('owners.create')
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
        $('#dt-owners').DataTable({
            pageLength: 10, deferRender: true,
            dom: '<"dt-top d-flex justify-content-between align-items-center gap-3 mb-2"lf>rt<"dt-bottom d-flex justify-content-end align-items-center mt-2"p>',
            order: [[0, 'asc']],
            columnDefs: [{ orderable: false, searchable: false, targets: -1 }],
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json', paginate: { previous: '&#8249;', next: '&#8250;' }
        }
        });
        });

        document.addEventListener('blur', function(e) {
        if (!e.target.classList.contains('email-check')) return;
        const input = e.target;
        const email = input.value.trim();
        const exclude = input.dataset.exclude || '';
        const errorDiv = document.getElementById(input.dataset.errorTarget);
        if (!email) { errorDiv.classList.add('d-none'); input.setCustomValidity(''); return; }
        fetch(`/owners/check-email?email=${encodeURIComponent(email)}&exclude=${exclude}`)
            .then(res => res.json())
            .then(data => {
            const msg = data.exists ? 'Ya existe un propietario con ese correo.' : '';
            errorDiv.textContent = msg;
            errorDiv.classList.toggle('d-none', !data.exists);
            input.setCustomValidity(msg);
            })
            .catch(() => { errorDiv.classList.add('d-none'); input.setCustomValidity(''); });
        }, true);
    </script>
@endpush
