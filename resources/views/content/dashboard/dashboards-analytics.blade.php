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


<style>

  /* Animación tarjetas */
  .dashboard-card {
    transition: all 0.3s ease;
  }

  .dashboard-card:hover {
    transform: translateY(-6px) scale(1.03);
    box-shadow: 0 18px 40px rgba(0,0,0,0.12);
  }

  /* Animación iconos */
  .dashboard-card i {
    transition: transform 0.3s ease;
  }

  .dashboard-card:hover i {
    transform: scale(1.2);
  }

</style>


<script>

document.addEventListener("DOMContentLoaded", function(){

  var serversOptions = {
    series: [{{ $serversOn }}, {{ $serversOff }}],
    chart: { type:'donut', height:350 },
    labels: ['Encendidos','Apagados'],
    colors: ['#28c76f','#ea5455'],
    legend: { position:'top' },
    stroke: { width:0 },
    plotOptions: {
      pie: {
        donut: { size:'70%' }
      }
    }
  };

  new ApexCharts(
    document.querySelector("#serversStatusChart"),
    serversOptions
  ).render();


  var machinesOptions = {
    series: [{{ $machinesOn }}, {{ $machinesOff }}],
    chart: { type:'donut', height:350 },
    labels: ['Encendidas','Apagadas'],
    colors: ['#28c76f','#ea5455'],
    legend: { position:'top' },
    stroke: { width:0 },
    plotOptions: {
      pie: {
        donut: { size:'70%' }
      }
    }
  };

  new ApexCharts(
    document.querySelector("#machinesStatusChart"),
    machinesOptions
  ).render();


  var appOptions = {
    series: [{
      name:'Servidores',
      data:{!! json_encode($appCounts) !!}
    }],
    chart:{ type:'bar', height:350, toolbar:{ show:false }},
    plotOptions:{ bar:{ borderRadius:12, columnWidth:'35%' }},
    dataLabels:{ enabled:false },
    grid:{ borderColor:'#f1f1f1', strokeDashArray:4 },
    legend:{ position:'top' },
    xaxis:{ categories:{!! json_encode($appNames) !!}},
    colors:['#7367f0']
  };

  new ApexCharts(
    document.querySelector("#serversByAppChart"),
    appOptions
  ).render();


  var dbOptions = {
    series: [{
      name:'Bases de Datos',
      data:{!! json_encode($dbCounts) !!}
    }],
    chart:{ type:'bar', height:350, toolbar:{ show:false }},
    plotOptions:{ bar:{ borderRadius:12, columnWidth:'35%' }},
    dataLabels:{ enabled:false },
    grid:{ borderColor:'#f1f1f1', strokeDashArray:4 },
    legend:{ position:'top' },
    xaxis:{ categories:{!! json_encode($dbNames) !!}},
    colors:['#00cfe8']
  };

  new ApexCharts(
    document.querySelector("#databaseTypeChart"),
    dbOptions
  ).render();

});

</script>

@endsection
