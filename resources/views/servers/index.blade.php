@extends('layouts/contentNavbarLayout')

@section('title', 'Servidores')

@section('content')

<div class="row">
<div class="col-12">
<div class="card">

<div class="card-header d-flex justify-content-between align-items-center">
<h5 class="mb-0">Servidores Activos</h5>

<div class="d-flex gap-2">

<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createServerModal">
<i class="bx bx-plus me-1"></i> Agregar servidor
</button>

<a href="{{ route('export','servers') }}" class="btn btn-primary">
Exportar Excel
</a>

<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadExcelModal">
Subir Excel
</button>

</div>
</div>

<div class="card-body">

<table id="dt-servers" class="table align-middle w-100">
<thead>
<tr>
<th>ID</th>
<th>Aplicacion</th>
<th>Hostname</th>
<th>Base de datos</th>
<th>Estado</th>
<th>IP primaria</th>
<th class="text-end">Acciones</th>
</tr>
</thead>
</table>

</div>
</div>
</div>
</div>

@include('servers.create')

<!-- MODAL EDITAR DINÁMICO -->
<div class="modal fade" id="editServerModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered modal-xl">
<div class="modal-content" id="editServerContent">
</div>
</div>
</div>
<div class="modal fade" id="showServerModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered modal-xl">
<div class="modal-content" id="showServerContent">
</div>
</div>
</div>

<!-- MODAL SUBIR EXCEL -->
<div class="modal fade" id="uploadExcelModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Subir archivo Excel</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('servers.import') }}" method="POST" enctype="multipart/form-data" id="excelUploadForm">
@csrf

<div class="modal-body">
<label class="form-label">Seleccionar archivo</label>
<input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
Cancelar
</button>

<button type="submit" class="btn btn-success">
Subir Excel
</button>
</div>

</form>

</div>
</div>
</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded",function(){

/* =========================
DATATABLE
========================= */

if(window.jQuery && $.fn.DataTable){

const table = $('#dt-servers').DataTable({

processing:true,
serverSide:true,
responsive:true,
autoWidth:false,
info:false,
pageLength:10,

ajax:{
url:"{{ route('servers.data') }}"
},

columns:[
{data:'id'},
{data:'application'},
{data:'hostname'},
{data:'database'},
{data:'state'},
{data:'ip'},
{data:'actions',orderable:false,searchable:false}
],

language:{
url:"https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
},

drawCallback:function(){

document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(el){
bootstrap.Dropdown.getOrCreateInstance(el);
});

}

});

}else{

console.error("jQuery o DataTables no se cargaron");

}

$(document).on("click",".delete-server-btn",function(){

let id = $(this).data("id");

Swal.fire({
title:"¿Eliminar servidor?",
text:"Esta acción no se puede deshacer",
icon:"warning",
showCancelButton:true,
confirmButtonText:"Sí, eliminar",
cancelButtonText:"Cancelar"
}).then((result)=>{

if(result.isConfirmed){

fetch("/servers/"+id,{
method:"DELETE",
headers:{
"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,
"Accept":"application/json"
}
})
.then(()=>{

Swal.fire("Servidor eliminado","","success");

$('#dt-servers').DataTable().ajax.reload(null,false);

});

}

});

});
/* =========================
ABRIR MODAL EDITAR (AJAX)
========================= */

$(document).on("click",".edit-server-btn",function(e){

e.preventDefault();

let id = $(this).data("id");

$("#editServerContent").html('<div class="p-5 text-center">Cargando...</div>');

$("#editServerContent").load("/servers/"+id+"/edit",function(){

let modal = new bootstrap.Modal(document.getElementById('editServerModal'));

modal.show();

});

});

$(document).on("click",".view-server-btn",function(e){

e.preventDefault();

let id = $(this).data("id");

$("#showServerContent").html('<div class="p-5 text-center">Cargando...</div>');

$("#showServerContent").load("/servers/"+id+"/show",function(){

let modal = new bootstrap.Modal(document.getElementById('showServerModal'));

modal.show();

});

});


$(document).on("click",".poweroff-server-btn",function(e){

e.preventDefault();

let id = $(this).data("id");

fetch("/servers/"+id+"/power-off",{
method:"POST",
headers:{
"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,
"Accept":"application/json"
}
})
.then(response=>response.json())
.then(data=>{

if(data.success){

Swal.fire("Servidor apagado","","success");

$('#dt-servers').DataTable().ajax.reload(null,false);

}

});

});


$(document).on("click",".delete-server-btn",function(){

let id = $(this).data("id");

Swal.fire({
title:"¿Eliminar servidor?",
text:"Esta acción no se puede deshacer",
icon:"warning",
showCancelButton:true,
confirmButtonText:"Sí, eliminar"
}).then((result)=>{

if(result.isConfirmed){

fetch("/servers/"+id,{
method:"DELETE",
headers:{
"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,
"Accept":"application/json"
}
})
.then(()=>{

Swal.fire("Servidor eliminado","","success");

$('#dt-servers').DataTable().ajax.reload(null,false);

});

}

});

});
/* =========================
TOM SELECT
========================= */

document.querySelectorAll('.server-searchable-select').forEach(function(select){

if(select.tomselect) return;

new TomSelect(select,{
create:false,
sortField:{field:'text',direction:'asc'}
});

});


/* =========================
EXCEL LOADING
========================= */

$('#excelUploadForm').on('submit',function(){

Swal.fire({
title:'Subiendo Excel',
text:'Procesando archivo...',
allowOutsideClick:false,
allowEscapeKey:false,
showConfirmButton:false,
didOpen:()=>Swal.showLoading()
});

});

});

</script>

@endpush
