<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Pagos Efectuados</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Filtros de búsqueda</h4>
                </div>
                <div class="card-body pt-1">
                    <form id="filters" class="row">
                        <div class='col-6'>
                            <div class="form-group">
                                <label>Fecha de inicio <code>*</code></label>
                                <input type="text" class="form-control" id="initial_date" name="initial_date"
                                    required>
                            </div>
                        </div>
                        <div class='col-6'>
                            <div class="form-group">
                                <label>Fecha de Final <code>*</code></label>
                                <input type="text" class="form-control" id="final_date" name="final_date" required>
                            </div>
                        </div>
                        <div class='col-12'>
                            <div class=" mt-3">
                                <div class="col-12 text-center">
                                    <button id="btn-filter" type="submit" href="#"
                                        class="btn btn-primary btn-icon icon-left"><i
                                            class="fas fa-search"></i>Generar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <div class="col-md-12 col-lg-12 col-sm-12" id="show-table">
            <div class="card">
                <div class="card-header">
                    <h4>Pagos Efectuados</h4>
                </div>
                <div class="card-body pt-1">
                    <div class="table-responsive">
                        <div id="loading-table" class="text-center" style="display: none">
                            <img class="mt-4" src="<?= asset('images/loading.gif') ?>" height="100" alt="">
                        </div>
                        <table class="table table-striped" id="table-invoices"></table>
                    </div>

                </div>
            </div>
        </div>

    </div>




    @pushOnce('css')
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
    @endPushOnce

    @pushOnce('scripts')
        <script src="//cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script src="<?= asset('theme/modules/bootstrap-daterangepicker/daterangepicker.js') ?>"></script>

        <script src="<?= asset('theme/modules/datatables/datatables.min.js') ?>"></script>
        <script src="<?= asset('theme/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') ?>"></script>
        <script src="<?= asset('theme/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') ?>"></script>
        <script src="<?= asset('theme/modules/jquery-datatables-checkboxes-1.2.12/js/dataTables.checkboxes.min.js') ?>">
        </script>

        <script>
            var toPriceFormat = function(data) {
                return (data) ?
                    Intl.NumberFormat('es-CO', {
                        style: 'currency',
                        currency: 'COP',
                        maximumFractionDigits: 0,
                        minimumFractionDigits: 0,
                    }).format(data) :
                    '$ 0';
            }


            let pdfBase64 = '';

            // click visualizarPDF button show  show-pdf if is show hidden
            $('#show-table').hide();


            $('#filters').submit(function(event) {
                event.preventDefault();

                $('#loading-table').show();
                $('#show-table').hide();
                $('#btn-filter').addClass('disabled btn-progress');

                let initial_date = $('#initial_date').val();
                let final_date = $('#final_date').val();

                if (!initial_date || !final_date) {
                    alert('Por favor complete los campos requeridos');
                    return;
                }

                // prepare url with params
                let url = '<?= route('documentos.pagos-efectuados-json') ?>';
                url += '?initial_date=' + initial_date;
                url += '&final_date=' + final_date;

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {

                        $('#btn-filter').removeClass('disabled btn-progress');
                        console.log(data.data.length)
                        console.log(data.data)
                        if(!data.data.length){
                            alert('No hay datos para mostrar');
                            return;
                        }
                        $('#show-table').show();
                        dt_invoices.clear().rows.add(data.data).draw();
                    },
                    error: function(xhr, status) {
                        dt_invoices.clear().draw();
                        $('#btn-filter').removeClass('disabled btn-progress');
                        $('#show-table').hide();

                        const message = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON
                            .message : 'Error comunicandonos con nuestra API, por favor intente mas tarde';

                        alert(message)
                    },
                    complete: function(xhr, status) {
                        $('#loading-table').hide();
                    }
                });

            });


            (function() {
                let daterangepicker_config = {
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'YYYY-MM-DD',
                        applyLabel: 'Aplicar',
                        cancelLabel: 'Cancelar',
                        fromLabel: 'Desde',
                        toLabel: 'Hasta',
                        customRangeLabel: 'Personalizado',
                        weekLabel: 'S',
                        daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                            'Septiembre',
                            'Octubre', 'Noviembre', 'Diciembre'
                        ],
                        firstDay: 1
                    },
                }

                $('#initial_date').daterangepicker({
                    ...daterangepicker_config,
                    startDate: moment().subtract(1, 'month').format('YYYY-MM-DD')
                });
                $('#final_date').daterangepicker(daterangepicker_config);

            })
            ();

            const format = (d) => {
                let keys = Object.keys(d);
                let values = Object.values(d);
                let html = '<table class="table table-bordered">';
                html += `<tr>
                    <th>#</th>
                    <th>Referencia</th>
                    <th>Fecha Contabilización</th>
                    <th>Total</th>
                    <th>Comentarios</th>
                </tr>`;

                // si no hay detalles mostrar mensaje
                if (!values.length) {
                    html += `<tr>
                        <td colspan="5" class="text-center">No hay detalles</td>
                    </tr>`;
                    html += '</table>';
                    return html;
                }
                for (let i = 0; i < keys.length; i++) {
                    html += `<tr>
                        <td>${values[i]['DocNum']}</td>
                        <td>${values[i]['NumAtCard']}</td>
                        <td>${values[i]['Fecha_Contabilizacion']}</td>
                        <td>${toPriceFormat(values[i]['DocTotal'])}</td>
                        <td>${values[i]['Comentarios']}</td>
                    </tr>`;
                }
                html += '</table>';
                return html;
            }

            var dt_invoices = $('#table-invoices').DataTable({
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
                        "title": "# Factura",
                        "data": "DocNum",
                    },
                    {
                        "title": "Fecha Contabilización",
                        "data": "Fecha_Contabilizacion",
                    },
                    {
                        "title": "Total",
                        "data": "DocTotal",
                        render: val => toPriceFormat(val),
                    },
                    {
                        'className': 'dt-control',
                        'title': 'Detalles',
                        'data': 'details',
                        render: function(data, type, row) {
                            if (!data.length) {
                                return 'Sin detalles';
                            }
                            return '<button class="btn btn-primary btn-icon icon-left toggle-details dt-control">Ver</button>';
                        },
                        defaultContent: ''
                    },
                ],
                dom: 'Bfrtip',
                buttons: [],
            });

            // Add event listener for opening and closing details
            dt_invoices.on('click', 'td.dt-control', function(e) {
                let tr = e.target.closest('tr');
                let row = dt_invoices.row(tr);
                let button = $(e.target).closest('button');

                if (row.child.isShown()) {
                    row.child.hide();
                    button.text('Ver');
                } else {
                    row.child(format(row.data()?.details)).show();
                    button.text('Ocultar');
                }
            });
        </script>
    @endPushOnce

</x-app-layout>
