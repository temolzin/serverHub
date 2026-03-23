@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('content')
<style>
    .chart-container {
        height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-container > div {
        width: 100%;
    }
</style>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title text-primary">
                        Bienvenido {{ auth()->user()->name }}
                    </h4>
                    <p class="mb-0">
                        Panel general del sistema donde puedes visualizar los recursos registrados.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ([
            ['Servidores', $servers, 'bx-server', 'primary'],
            ['Bases de Datos', $databases, 'bx-data', 'success'],
            ['Aplicaciones', $applications, 'bx-layer', 'info'],
            ['Propietarios', $owners, 'bx-user', 'warning'],
            ['Instancias', $instances, 'bx-cube', 'secondary'],
            ['Storage', $storages, 'bx-hdd', 'danger'],
            ['GCP Machines', $machines, 'bx-cloud', 'primary'],
            ['Usuarios', $users, 'bx-group', 'dark'],
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
                <div class="card-header">
                    <h5 class="fw-semibold">Máquinas parcheadas</h5>
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
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h5>Estado de Servidores</h5></div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="serversStatusChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h5>Estado de Máquinas</h5></div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="machinesStatusChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h5>Servidores por Tipo</h5></div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="serversByAppChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h5>Bases de Datos por Tipo</h5></div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="databaseTypeChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5>GCP Machines por Kernel Version</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="redhatChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5>GCP Machines por Sistema Operativo</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="osChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5>Servidores por Sistema Operativo</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="serversOSChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        function groupTopData(labels, data, limit = 5) {
            let combined = labels.map((label, i) => ({
                label: label,
                value: data[i]
            }));

            combined.sort((a, b) => b.value - a.value);

            let top = combined.slice(0, limit);
            let rest = combined.slice(limit);

            let otherSum = rest.reduce((sum, item) => sum + item.value, 0);

            if (otherSum > 0) {
                top.push({ label: 'Otros', value: otherSum });
            }

            return {
                labels: top.map(i => i.label),
                data: top.map(i => i.value)
            };
        }

        function createDonut(el, labels, data, colors) {
            return new ApexCharts(document.querySelector(el), {
                series: data,
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: labels,
                colors: colors,
                legend: {
                    position: 'bottom',
                    fontSize: '13px'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%'
                        }
                    }
                },
                dataLabels: {
                    formatter: function(val) {
                        return val.toFixed(1) + "%";
                    }
                }
            }).render();
        }

        createDonut("#serversStatusChart",
            ['Encendidos', 'Apagados'],
            [{{ $serversOn }}, {{ $serversOff }}],
            ['#28c76f', '#ea5455']
        );

        createDonut("#machinesStatusChart",
            ['Encendidas', 'Apagadas'],
            [{{ $machinesOn }}, {{ $machinesOff }}],
            ['#28c76f', '#ea5455']
        );

        createDonut("#serversByAppChart",
            {!! json_encode($appNames) !!},
            {!! json_encode($appCounts) !!},
            ['#7367f0', '#00cfe8', '#ff9f43', '#28c76f']
        );

        createDonut("#databaseTypeChart",
            {!! json_encode($dbNames) !!},
            {!! json_encode($dbCounts) !!},
            ['#00cfe8', '#ff9f43', '#7367f0']
        );

        let kernelData = groupTopData(
            {!! json_encode($redhatLabels) !!},
            {!! json_encode($redhatCounts) !!}
        );

        createDonut("#redhatChart",
            kernelData.labels,
            kernelData.data,
            ['#ff9f43', '#7367f0', '#00cfe8', '#28c76f', '#ea5455', '#999']
        );

        let osData = groupTopData(
            {!! json_encode($osLabels) !!},
            {!! json_encode($osCounts) !!}
        );

        createDonut("#osChart",
            osData.labels,
            osData.data,
            ['#00cfe8', '#7367f0', '#ff9f43', '#28c76f', '#ea5455', '#999']
        );

        let serversOSData = groupTopData(
            {!! json_encode($serverOSLabels) !!},
            {!! json_encode($serverOSCounts) !!}
        );

        createDonut("#serversOSChart",
            serversOSData.labels,
            serversOSData.data,
            ['#7367f0', '#00cfe8', '#ff9f43', '#28c76f', '#ea5455', '#999']
        );

        document.getElementById("filterForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('dashboard.filter') }}?" + new URLSearchParams(formData), {
                method: "GET"
            })
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById("patchedTable");
                tbody.innerHTML = "";

                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No se encontraron resultados
                            </td>
                        </tr>`;
                    return;
                }

                data.forEach(machine => {
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
    });
</script>
@endsection
