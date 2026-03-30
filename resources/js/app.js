import './bootstrap';
import $ from 'jquery';
import 'bootstrap';
import 'perfect-scrollbar';
import '../assets/vendor/js/menu';
import '../assets/js/main';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.css';
import '../css/app.css';
DataTable.use($);
$(function () {
    $('.datatable').each(function () {
        $(this).DataTable({
            responsive: true,
            info: false,
            pagingType: 'simple_numbers',
            columnDefs: [
                { responsivePriority: 1, targets: 0, className: 'text-start' },
                { responsivePriority: 2, targets: -1, orderable: false }
            ],
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible",
                "sSearch": "Buscar:",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sNext": "<i class='bx bx-chevron-right'></i>",
                    "sPrevious": "<i class='bx bx-chevron-left'></i>"
                }
            },
            dom: '<"row align-items-center mb-3"<"col-md-6 d-flex justify-content-center justify-content-md-start mb-3 mb-md-0"l><"col-md-6 d-flex flex-wrap justify-content-center justify-content-md-end gap-2"f>>' +
                    't' +
                    '<"row mt-3"<"col-12 d-flex justify-content-center justify-content-md-end"p>>',
            drawCallback: function () {
                $('.pagination').addClass('pagination-rounded');
            }
        });
    });
});
