@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title text-primary">Bienvenido {{ auth()->user()->full_name }}</h4>
                    <p class="mb-0">Panel general del sistema donde puedes visualizar los recursos registrados.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ([
            ['On-Premise',$servers,'bx-server','primary'],
            ['Bases de Datos',$databases,'bx-data','success'],
            ['Aplicaciones',$applications,'bx-layer','info'],
            ['Propietarios',$owners,'bx-user','warning'],
            ['Instancias',$instances,'bx-cube','secondary'],
            ['Storage',$storages,'bx-hdd','danger'],
            ['GCP Máquinas',$machines,'bx-cloud','primary'],
            ['Usuarios',$users,'bx-group','dark'],
        ] as [$label, $value, $icon, $color])
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card dashboard-card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">{{ $label }}</p>
                            <h3 class="fw-bold">{{ $value }}</h3>
                        </div>
                        <div class="bg-label-{{ $color }} rounded p-3">
                            <i class="bx {{ $icon }} fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h5 class="fw-semibold">Filtro de parcheo por fechas</h5>
                </div>
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Fecha inicial</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha final</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100 shadow-sm">
                                <i class="bx bx-search me-1"></i> Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">Máquinas parcheadas</h5>
                    <a id="exportPatchedMachinesBtn" href="{{ route('dashboard.patched.export') }}"class="btn btn-success btn-sm disabled" aria-disabled="true"><i class="bx bx-export me-1"></i> Exportar Excel</a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Maquina</th>
                                <th>IP</th>
                                <th>Sistema operativo</th>
                                <th>Kernel</th>
                                <th>Ultimo Parche</th>
                            </tr>
                        </thead>
                        <tbody id="patchedTable">
                            @foreach ($patchedMachines as $machine)
                                <tr>
                                    <td>{{ $machine->machine_name }}</td>
                                    <td>{{ filled($machine->internal_ip) ? $machine->internal_ip : 'N/A' }}</td>
                                    <td>{{ $machine->operations_system }}</td>
                                    <td>{{ $machine->kernel_version }}</td>
                                    <td>{{ $machine->latest_security_patch }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0">
                    <h5 class="fw-semibold">Estado de Servidores</h5>
                </div>
                <div class="card-body">
                    <div id="serversStatusChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0">
                    <h5 class="fw-semibold">Estado de Máquinas GCP</h5>
                </div>
                <div class="card-body">
                    <div id="machinesStatusChart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0">
                    <h5 class="fw-semibold">Servidores por Tipo de Aplicación</h5>
                </div>
                <div class="card-body">
                    <div id="serversByAppChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0">
                    <h5 class="fw-semibold">Bases de Datos por Tipo</h5>
                </div>
                <div class="card-body">
                    <div id="databaseTypeChart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0">
                    <h5 class="fw-semibold">Versiones de Kernel (Red Hat)</h5>
                </div>
                <div class="card-body">
                    <div id="redhatChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0">
                    <h5 class="fw-semibold">SO según VMware (GCP)</h5>
                </div>
                <div class="card-body">
                    <div id="osChart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0">
                    <h5 class="fw-semibold">SO de Servidores</h5>
                </div>
                <div class="card-body">
                    <div id="serversOSChart"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function groupTopData(labels, data, limit = 5) {
                let combined = labels.map((label, i) => ({ label, value: data[i] }));
                combined.sort((a, b) => b.value - a.value);
                let top = combined.slice(0, limit);
                let rest = combined.slice(limit);
                let otherSum = rest.reduce((sum, item) => sum + item.value, 0);
                if (otherSum > 0) top.push({ label: 'Otros', value: otherSum });
                return {
                    labels: top.map(i => i.label),
                    data: top.map(i => i.value),
                };
            }

            function createDonut(el, labels, data, colors) {
                return new ApexCharts(document.querySelector(el), {
                    series: data,
                    chart: { type: 'donut', height: 300 },
                    labels,
                    colors,
                    legend: { position: 'bottom', fontSize: '13px' },
                    plotOptions: { pie: { donut: { size: '70%' } } },
                    dataLabels: {
                        formatter: val => val.toFixed(1) + '%',
                    },
                }).render();
            }

            const filterForm  = document.getElementById('filterForm');
            const exportBtn   = document.getElementById('exportPatchedMachinesBtn');
            const baseUrl     = '{{ route('dashboard.patched.export') }}';

            function disableExport() {
                if (!exportBtn) return;
                exportBtn.classList.add('disabled');
                exportBtn.setAttribute('aria-disabled', 'true');
            }

            function enableExport() {
                if (!exportBtn) return;
                exportBtn.classList.remove('disabled');
                exportBtn.removeAttribute('aria-disabled');
            }

            function updateExportLink(startDate, endDate) {
                if (!exportBtn) return;
                const valid = startDate && endDate;
                const params = valid ? '?' + new URLSearchParams({ start_date: startDate, end_date: endDate }).toString() : '';
                exportBtn.href = baseUrl + params;
                valid ? enableExport() : disableExport();
            }

            if (exportBtn) {
                exportBtn.addEventListener('click', function (e) {
                    if (this.classList.contains('disabled')) e.preventDefault();
                });
            }

            if (filterForm) {
                const startDateInput = filterForm.querySelector('input[name="start_date"]');
                const endDateInput   = filterForm.querySelector('input[name="end_date"]');
                updateExportLink(startDateInput?.value, endDateInput?.value);
                [startDateInput, endDateInput].forEach(input => {
                    if (!input) return;
                    input.addEventListener('change', () => updateExportLink(startDateInput?.value, endDateInput?.value));
                });

                filterForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData  = new FormData(this);
                    const startDate = formData.get('start_date');
                    const endDate   = formData.get('end_date');
                    updateExportLink(startDate, endDate);
                    fetch('{{ route('dashboard.filter') }}?' + new URLSearchParams(formData))
                        .then(res => res.json())
                        .then(data => {
                            const tbody = document.getElementById('patchedTable');
                            const rows  = Array.isArray(data) ? data : (Array.isArray(data.data) ? data.data : []);
                            tbody.innerHTML = '';
                            if (rows.length === 0) {
                                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No se encontraron resultados</td></tr>`;
                                disableExport();
                                return;
                            }
                            enableExport();
                            rows.forEach(machine => {
                                tbody.innerHTML += `
                                    <tr>
                                        <td>${machine.machine_name}</td>
                                        <td>${machine.internal_ip ?? 'N/A'}</td>
                                        <td>${machine.operations_system}</td>
                                        <td>${machine.kernel_version}</td>
                                        <td>${machine.latest_security_patch}</td>
                                    </tr>`;
                            });
                        });
                });
            }

            createDonut('#serversStatusChart',
                ['Encendidos', 'Apagados'],
                [{{ $serversOn }}, {{ $serversOff }}],
                ['#28c76f', '#ea5455']
            );

            createDonut('#machinesStatusChart',
                ['Encendidas', 'Apagadas'],
                [{{ $machinesOn }}, {{ $machinesOff }}],
                ['#28c76f', '#ea5455']
            );

            createDonut('#serversByAppChart',
                {!! json_encode($appNames) !!},
                {!! json_encode($appCounts) !!},
                ['#7367f0', '#00cfe8', '#ff9f43', '#28c76f']
            );

            createDonut('#databaseTypeChart',
                {!! json_encode($dbNames) !!},
                {!! json_encode($dbCounts) !!},
                ['#00cfe8', '#ff9f43', '#7367f0']
            );

            const kernelData = groupTopData(
                {!! json_encode($redhatLabels) !!},
                {!! json_encode($redhatCounts) !!}
            );
            createDonut('#redhatChart', kernelData.labels, kernelData.data, ['#ff9f43', '#7367f0', '#00cfe8', '#28c76f', '#ea5455', '#999']);

            const osData = groupTopData(
                {!! json_encode($osLabels) !!},
                {!! json_encode($osCounts) !!}
            );
            createDonut('#osChart', osData.labels, osData.data, ['#00cfe8', '#7367f0', '#ff9f43', '#28c76f', '#ea5455', '#999']);

            const serversOSData = groupTopData(
                {!! json_encode($serverOSLabels) !!},
                {!! json_encode($serverOSCounts) !!}
            );
            createDonut('#serversOSChart', serversOSData.labels, serversOSData.data, ['#7367f0', '#00cfe8', '#ff9f43', '#28c76f', '#ea5455', '#999']);
        });
    </script>
@endpush
