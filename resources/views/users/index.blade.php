<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Lista de Usuarios</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12" id="show-pdf">
            <div class="card">
                <div class="card-header">
                    <h4>Lista de Usuarios</h4>
                </div>
                <div class="card-body pt-1">
                    <div class="table-responsive">
                        <div id="loading-table" class="text-center" style="display: none">
                            <img class="mt-4" src="<?= asset('images/loading.gif') ?>" height="100" alt="">
                        </div>
                        <table class="table table-striped" id="table-users"></table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <link href="//cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('theme/modules/bootstrap-daterangepicker/daterangepicker.css') ?>">

    <link rel="stylesheet" href="<?= asset('theme/modules/datatables/datatables.min.css') ?>">
    <link rel="stylesheet"
        href="<?= asset('theme/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('theme/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') ?>">
    <link rel="stylesheet"
        href="<?= asset('theme/modules/jquery-datatables-checkboxes-1.2.12/css/dataTables.checkboxes.css') ?>">

    <style>
        .card-totals-provisiones .card-header {
            padding-top: 5px;
            padding-bottom: 5px;
            min-height: inherit;
        }

        .card-totals-provisiones .card-body {
            padding-top: 5px;
            padding-bottom: 5px;
            min-height: inherit;
        }

        table.dataTable tbody>tr.selected {
            background-color: #ff8e63;
        }

        #loading-table {
            position: absolute;
            background: rgba(0, 0, 0, 0.1);
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            border-radius: 3px;
            cursor: default;
            user-select: none;
        }

        .ts-control {
            top: -1px;
            left: -1px;
            position: absolute;
            border-radius: 0.25rem;
            height: calc(100% + 2px);
            width: calc(100% + 2px);
            border: 1px solid #ebe1dd;
            padding: 10px 15px;
        }

        .ts-dropdown .active {
            background-color: #ed6d3c;
            color: #fff;
        }

        .ts-dropdown .option {
            padding: 10px 15px;
        }

        .ts-dropdown,
        .ts-control,
        .ts-control input {
            color: #495057;
            font-size: 14px;
        }

        .daterangepicker .calendar-table .today:not(.active) {
            background-color: whitesmoke;
            font-weight: bold;
            color: inherit;
        }
    </style>

    @pushOnce('scripts')
        <script src="//cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script src="<?= asset('theme/modules/bootstrap-daterangepicker/daterangepicker.js') ?>"></script>

        <script src="<?= asset('theme/modules/datatables/datatables.min.js') ?>"></script>
        <script src="<?= asset('theme/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') ?>"></script>
        <script src="<?= asset('theme/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') ?>"></script>
        <script src="<?= asset('theme/modules/jquery-datatables-checkboxes-1.2.12/js/dataTables.checkboxes.min.js') ?>">
        </script>
        <script>
            var dt_users = $('#table-users').DataTable({
                language: {
                    url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
                    select: {
                        rows: {
                            '_': ' %d registros seleccionados',
                            '0': '',
                            '1': ' %d registro seleccionado'
                        }
                    }
                },
                paging: true,
                columns: [{
                        "title": "ID",
                        "data": "CardCode",
                    },
                    {
                        "title": "Nombre",
                        "data": "CardName",
                    },
                    {
                        "title": "Email",
                        "data": "E_Mail",
                    },
                    {
                        "title": "Celular",
                        "data": "Cellular",
                    },
                    {
                        "title": "Acción",
                        "data": "CardCode",
                        render: val => {
                            console.log(val)
                            return `<a href="<?= route('users.impersonate') ?>?user_id=${val}" class="btn btn-primary btn-sm">Impersonar</a>`;
                        },
                    }
                ],
                dom: 'Bfrtip',
                buttons: [],
            });

            // DOM cargado ejecutar
            $(document).ready(function() {
                $('#loading-table').show();
                let url = '<?= route('users.json') ?>';
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        dt_users.clear().rows.add(data?.data).draw();
                    },
                    error: function(xhr, status) {
                        dt_invoices.clear().draw();
                        alert('Error comunicandonos con nuestra API, por favor intente mas tarde')
                    },
                    complete: function(xhr, status) {
                        $('#loading-table').hide();
                    }
                });
            });


        </script>
    @endPushOnce


</x-app-layout>
