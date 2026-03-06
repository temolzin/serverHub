<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
  rel="stylesheet">
<!-- Fonts Icons -->
@vite(['resources/assets/vendor/fonts/iconify/iconify.css'])
<!-- Core CSS -->
@vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css'])
<!-- Vendor Styles -->
@vite('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    table[id^="dt-"]:not(.dataTable) {
        display: none;
    }

    .dt-loading {
        text-align: center;
        padding: 3rem 1rem;
        color: #697a8d;
        font-size: .875rem;
    }

    .dt-loading::before {
        content: '';
        display: block;
        width: 2rem;
        height: 2rem;
        margin: 0 auto .75rem;
        border: 3px solid #e7e7ff;
        border-top-color: #696cff;
        border-radius: 50%;
        animation: dt-spin .6s linear infinite;
    }

    @keyframes dt-spin {
        to {
        transform: rotate(360deg);
        }
    }

    .dataTables_wrapper+.dt-loading,
    .dataTables_wrapper~.dt-loading {
        display: none;
    }

    table[id^="dt-"] td:nth-child(2),
    table[id^="dt-"] th:nth-child(2) {
        display: none;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        border: 1px solid #d9dee3;
        border-radius: .375rem;
        padding: .375rem .75rem;
        font-size: .875rem;
        color: #697a8d;
        background-color: #fff;
        transition: border-color .15s ease-in-out;
    }

    div.dataTables_wrapper div.dataTables_filter input:focus {
        border-color: #696cff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(105, 108, 255, .25);
    }

    div.dataTables_wrapper div.dataTables_length select {
        border: 1px solid #d9dee3;
        border-radius: .375rem;
        padding: .25rem .5rem;
        font-size: .875rem;
        color: #697a8d;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
        text-align: center;
    }

    div.dataTables_wrapper div.dataTables_info {
        font-size: .875rem;
        color: #697a8d;
        padding-top: .5rem;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button .page-link,
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.page-item .page-link {
        border-radius: 50% !important;
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        padding: 0 !important;
        line-height: 32px !important;
        text-align: center !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 2px;
        font-size: .875rem;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button {
        padding: 0 !important;
        margin: 0 2px;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
        background: #696cff !important;
        color: #fff !important;
        border-color: #696cff !important;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
        background: #f0f0ff !important;
        color: #696cff !important;
        border-color: #d9dee3 !important;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled,
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled:hover {
        color: #adb6be !important;
        background: transparent !important;
        border-color: transparent !important;
    }

    table.dataTable thead>tr>th {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #697a8d;
        font-weight: 600;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #d9dee3;
    }

    div.dataTables_wrapper>.row {
        --bs-gutter-x: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        overflow: hidden;
    }

    div.dataTables_wrapper>.row>[class*="col-"] {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    div.dataTables_wrapper {
        overflow: visible !important;
    }

    div.dataTables_wrapper {
        width: 100%;
        overflow-x: auto;
    }

    div.dataTables_scroll {
        overflow: visible !important;
    }

    .table-responsive:has(table.dataTable) {
        overflow: visible !important;
    }

    div.dataTables_wrapper {
        overflow-x: auto;
        overflow-y: visible;
    }
</style>
@yield('vendor-style')
<!-- Page Styles -->
@yield('page-style')
<!-- app CSS -->
@vite(['resources/css/app.css'])
<!-- END: app CSS-->
