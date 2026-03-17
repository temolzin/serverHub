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
     <div class="col-md-3 col-sm-6 mb-4">
       <div class="card dashboard-card shadow-sm border-0 h-100">
         <div class="card-body d-flex justify-content-between align-items-center">
           <div>
             <p class="text-muted mb-1">Servidores</p>
             <h3 class="fw-bold">{{ $servers }}</h3>
           </div>
           <div class="bg-label-primary rounded p-3">
             <i class="bx bx-server fs-3"></i>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-3 col-sm-6 mb-4">
       <div class="card dashboard-card shadow-sm border-0 h-100">
         <div class="card-body d-flex justify-content-between align-items-center">
           <div>
             <p class="text-muted mb-1">Bases de Datos</p>
             <h3 class="fw-bold">{{ $databases }}</h3>
           </div>
           <div class="bg-label-success rounded p-3">
             <i class="bx bx-data fs-3"></i>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-3 col-sm-6 mb-4">
       <div class="card dashboard-card shadow-sm border-0 h-100">
         <div class="card-body d-flex justify-content-between align-items-center">
           <div>
             <p class="text-muted mb-1">Aplicaciones</p>
             <h3 class="fw-bold">{{ $applications }}</h3>
           </div>
           <div class="bg-label-info rounded p-3">
             <i class="bx bx-layer fs-3"></i>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-3 col-sm-6 mb-4">
       <div class="card dashboard-card shadow-sm border-0 h-100">
         <div class="card-body d-flex justify-content-between align-items-center">
           <div>
             <p class="text-muted mb-1">Propietarios</p>
             <h3 class="fw-bold">{{ $owners }}</h3>
           </div>
           <div class="bg-label-warning rounded p-3">
             <i class="bx bx-user fs-3"></i>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-3 col-sm-6 mb-4">
       <div class="card dashboard-card shadow-sm border-0 h-100">
         <div class="card-body d-flex justify-content-between align-items-center">
           <div>
             <p class="text-muted mb-1">Instancias</p>
             <h3 class="fw-bold">{{ $instances }}</h3>
           </div>
           <div class="bg-label-secondary rounded p-3">
             <i class="bx bx-cube fs-3"></i>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-3 col-sm-6 mb-4">
       <div class="card dashboard-card shadow-sm border-0 h-100">
         <div class="card-body d-flex justify-content-between align-items-center">
           <div>
             <p class="text-muted mb-1">Storage</p>
             <h3 class="fw-bold">{{ $storages }}</h3>
           </div>
           <div class="bg-label-danger rounded p-3">
             <i class="bx bx-hdd fs-3"></i>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-3 col-sm-6 mb-4">
       <div class="card dashboard-card shadow-sm border-0 h-100">
         <div class="card-body d-flex justify-content-between align-items-center">
           <div>
             <p class="text-muted mb-1">GCP Machines</p>
             <h3 class="fw-bold">{{ $machines }}</h3>
           </div>
           <div class="bg-label-primary rounded p-3">
             <i class="bx bx-cloud fs-3"></i>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-3 col-sm-6 mb-4">
       <div class="card dashboard-card shadow-sm border-0 h-100">
         <div class="card-body d-flex justify-content-between align-items-center">
           <div>
             <p class="text-muted mb-1">Usuarios</p>
             <h3 class="fw-bold">{{ $users }}</h3>
           </div>
           <div class="bg-label-dark rounded p-3">
             <i class="bx bx-group fs-3"></i>
           </div>
         </div>
       </div>
     </div>
   </div>
   <div class="row mb-4">
     <div class="col-12">
       <div class="card shadow-sm border-0 mb-4">
         <div class="card-header">
           <h5 class="fw-semibold">Filtro de parcheo por fechas</h5>
         </div>
         <div class="card-body">
           <form method="GET" class="row g-3">
             <div class="col-md-4">
               <label class="form-label">Fecha inicial</label>
               <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
             </div>
             <div class="col-md-4">
               <label class="form-label">Fecha final</label>
               <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
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
           <h5 class="fw-semibold">Máquinas parcheadas por rango de fechas</h5>
         </div>
         <div class="card-body">
           <table class="table">
             <thead>
               <tr>
                 <th>Maquina</th>
                 <th>Sistema operativo</th>
                 <th>Kernel</th>
                 <th>Ultimo Parche</th>
               </tr>
             </thead>
             <tbody>
               @if ($patchedMachines->count() > 0)
                 @foreach ($patchedMachines as $machine)
                   <tr>
                     <td>{{ $machine->machine_name }}</td>
                     <td>{{ $machine->operations_system }}</td>
                     <td>{{ $machine->kernel_version }}</td>
                     <td>{{ $machine->latest_security_patch }}</td>
                   </tr>
                 @endforeach
               @else
                 <tr>
                   <td colspan="4" class="text-center text-muted">
                     No se encontraron máquinas en el rango de parches seleccionado
                   </td>
                 </tr>
               @endif
             </tbody>
           </table>
         </div>
       </div>
     </div>
   </div>
   <div class="row">
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
           <h5 class="fw-semibold">Estado de Máquinas</h5>
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
         <div class="card-header">
           <h5>GCP Machines por Kernel Version</h5>
         </div>
         <div class="card-body">
           <div id="redhatChart"></div>
         </div>
       </div>
     </div>
     <div class="col-lg-6 mb-4">
       <div class="card shadow-sm border-0">
         <div class="card-header">
           <h5>GCP Machines por Sistema Operativo</h5>
         </div>
         <div class="card-body">
           <div id="osChart"></div>
         </div>
       </div>
     </div>
   </div>
   <script>
     document.addEventListener("DOMContentLoaded", function() {
       new ApexCharts(document.querySelector("#serversStatusChart"), {
         series: [{{ $serversOn }}, {{ $serversOff }}],
         chart: {
           type: 'donut',
           height: 350
         },
         labels: ['Encendidos', 'Apagados'],
         colors: ['#28c76f', '#ea5455']
       }).render();
       new ApexCharts(document.querySelector("#machinesStatusChart"), {
         series: [{{ $machinesOn }}, {{ $machinesOff }}],
         chart: {
           type: 'donut',
           height: 350
         },
         labels: ['Encendidas', 'Apagadas'],
         colors: ['#28c76f', '#ea5455']
       }).render();
       new ApexCharts(document.querySelector("#serversByAppChart"), {
         series: [{
           name: 'Servidores',
           data: {!! json_encode($appCounts) !!}
         }],
         chart: {
           type: 'bar',
           height: 350
         },
         xaxis: {
           categories: {!! json_encode($appNames) !!}
         },
         colors: ['#7367f0']
       }).render();
       new ApexCharts(document.querySelector("#databaseTypeChart"), {
         series: [{
           name: 'Bases de Datos',
           data: {!! json_encode($dbCounts) !!}
         }],
         chart: {
           type: 'bar',
           height: 350
         },
         xaxis: {
           categories: {!! json_encode($dbNames) !!}
         },
         colors: ['#00cfe8']
       }).render();
       new ApexCharts(document.querySelector("#redhatChart"), {
         series: [{
           name: 'Machines',
           data: {!! json_encode($redhatCounts) !!}
         }],
         chart: {
           type: 'bar',
           height: 350
         },
         xaxis: {
           categories: {!! json_encode($redhatLabels) !!}
         },
         colors: ['#ff9f43']
       }).render();
       new ApexCharts(document.querySelector("#osChart"), {
         series: [{
           name: 'Machines',
           data: {!! json_encode($osCounts) !!}
         }],
         chart: {
           type: 'bar',
           height: 350
         },
         xaxis: {
           categories: {!! json_encode($osLabels) !!}
         },
         colors: ['#00cfe8']
       }).render();

     });
   </script>
 @endsection
