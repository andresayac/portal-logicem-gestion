<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Provisiones</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-6">

            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Filtros de búsqueda</h4>
                </div>

                <div class="card-body pt-1">

                    <form id="filters">

                        <div class="form-group mb-2">
                            <label>Fecha inicial <code>*</code></label>
                            <input required type="text" class="form-control form-control-sm" id="fecha_inicial" name="fecha_inicial">
                        </div>

                        <div class="form-group mb-2">
                            <label>Fecha final <code>*</code></label>
                            <input required type="text" class="form-control form-control-sm" id="fecha_final" name="fecha_final">
                        </div>

                        <div class="form-group mb-2">
                            <label>Cliente</label>
                            <select class="form-control form-control-sm" id="select-clientes">
                                <option value="">Todos</option>
                            </select>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-right">
                                <button id="btn-filter" type="submit" href="#" class="btn btn-primary btn-icon icon-left"><i class="fas fa-search"></i>
                                    Buscar</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        <div class="col-6">

            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Ejecución</h4>
                </div>

                <div class="card-body pt-1">

                    <div class="form-group mb-2">
                        <label>Fecha asiento <code>*</code></label>
                        <input required type="text" class="form-control form-control-sm" id="fecha_asiento" name="fecha_asiento">
                    </div>

                    <div class="mt-3 text-right">
                        <button id="procesar" type="button" class="btn btn-primary btn-icon icon-left">
                            <i class="fas fa-vote-yea"></i> Procesar seleccionados
                        </button>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-primary card-totals-provisiones">
                <div class="card-header">
                    <h4>Total Flete</h4>
                </div>
                <div class="card-body">
                    <h6 id="text-total-flete">$ 0</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-primary card-totals-provisiones">
                <div class="card-header">
                    <h4>Total provisión</h4>
                </div>
                <div class="card-body">
                    <h6 id="text-total-provision">$ 0</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-primary card-totals-provisiones">
                <div class="card-header">
                    <h4>Total</h4>
                </div>
                <div class="card-body">
                    <h6 id="text-total">$ 0</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Provisiones</h4>
                </div>

                <div class="card-body pt-1">

                    <div class="table-responsive">
                        <div id="loading-table" class="text-center" style="display: none">
                            <img class="mt-4" src="<?= asset('images/loading.gif') ?>" height="100" alt="">
                        </div>
                        <table class="table table-striped" id="table-provisiones"></table>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @pushOnce('css')
    <link href="//cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('theme/modules/bootstrap-daterangepicker/daterangepicker.css') ?>">

    <link rel="stylesheet" href="<?= asset('theme/modules/datatables/datatables.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('theme/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('theme/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('theme/modules/jquery-datatables-checkboxes-1.2.12/css/dataTables.checkboxes.css') ?>">

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

        var dt_provisiones = $('#table-provisiones').DataTable({

            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
                select: {
                    rows: {
                        '_': ' %d registros seleccionados',
                        '0': '',
                        '1': ' %d registro seleccionado'
                    }
                }
            },
            select: {
                style: 'multi',
            },
            paging: false,
            // searching: false,
            // serverSide: true,
            // ordering: false,
            // deferLoading: 0,
            // ajax: {
            //     url: '<?= route('provisiones.getProvisiones') ?>',
            //     type: 'GET',
            //     headers: {
            //         'X-CSRF-TOKEN': '<?= csrf_token() ?>',
            //     },
            //     beforeSend: function() {},
            //     error: function() {},
            //     complete: function(xhr, status) {
            //         $('#loading-table').hide();
            //         $('#btn-filter').prop('disabled', false);
            //     }
            // },
            columnDefs: [{
                'targets': 0,
                'checkboxes': {
                    'selectRow': true
                }
            }],
            columns: [{
                    "title": "index",
                    "data": "index",
                },
                {
                    "title": "Socio de Negocio",
                    "data": "Socio de Negocio",
                },
                {
                    "title": "Remesa",
                    "data": "Remesa",
                },
                {
                    "title": "Manifiesto",
                    "data": "Manifiesto",
                },
                {
                    "title": "Estado SAP",
                    "data": "Estado SAP",
                },
                {
                    "title": "Fecha",
                    "data": "Fecha",
                },
                {
                    "title": "Almacen",
                    "data": "Almacen",
                },
                {
                    "title": "Flete",
                    "data": "Flete",
                    render: val => toPriceFormat(val),
                },
                {
                    "title": "Provision",
                    "data": "Provision",
                    render: val => toPriceFormat(val),
                },
                {
                    "title": "Valor Total",
                    "data": "Valor Total",
                    render: val => toPriceFormat(val),
                },
                {
                    "title": "Margen",
                    "data": "Margen_Nuevo",
                    render: val => parseInt(val),
                },
            ],
            dom: 'Bfrtip',
            buttons: [],
        });

        $('#procesar').click(function(e) {
            e.preventDefault();

            let rows_selected = dt_provisiones.column(0).checkboxes.selected();

            if (rows_selected.length === 0)
                return alert('Debe seleccionar al menos un registro.');

            FSL.show();

            let rows_selected_data = [];

            $.each(rows_selected, function(index, rowId) {
                selected_row_index = rowId - 1;
                let data = dt_provisiones.row(selected_row_index).data();
                rows_selected_data.push(data);
            });

            let fecha_asiento = $('#fecha_asiento').val();

            $.ajax({
                url: '<?= route('provisiones.sendProvisiones') ?>',
                type: 'POST',
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({
                    fecha_asiento: fecha_asiento,
                    provisiones: rows_selected_data,
                    fecha_inicial: $('#fecha_inicial').val(),
                    fecha_final: $('#fecha_final').val(),
                }),
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },
                success: function(data) {
                    window.location.href = "<?= route('provisiones.log') ?>";
                },
                error: function(xhr, status) {
                    if (xhr.responseJSON.error)
                        alert(xhr.responseJSON.error)

                    FSL.hide();

                },
                complete: function(xhr, status) {
                    // complete
                }
            });

        });

        (function() {
            let daterangepicker_config = {
                singleDatePicker: true,
                showDropdowns: true,
                maxDate: moment().add(0, 'days'),
                locale: {
                    format: 'YYYY/MM/DD',
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

            $('#fecha_inicial').daterangepicker({
                ...daterangepicker_config,
                startDate: moment().startOf('month')
            });
            $('#fecha_final').daterangepicker(daterangepicker_config);

            $('#fecha_asiento').daterangepicker({
                ...daterangepicker_config,
                startDate: moment().startOf('month'),
                minDate: $('#fecha_inicial').val(),
            });

            $('#fecha_inicial').on('change', function() {
                let minDate = moment($(this).val(), 'YYYY/MM/DD');
                let fechaAsientoMoment = moment($('#fecha_asiento').val(), 'YYYY/MM/DD');
                $('#fecha_asiento').data('daterangepicker').minDate = minDate
                if (fechaAsientoMoment.isBefore(minDate)) {
                    $('#fecha_asiento').val(minDate.format('YYYY/MM/DD'));
                }
            });

            $('#fecha_final').on('change', function() {
                let maxDate = moment($(this).val(), 'YYYY/MM/DD');
                let fechaAsientoMoment = moment($('#fecha_asiento').val(), 'YYYY/MM/DD');
                $('#fecha_asiento').data('daterangepicker').maxDate = maxDate
                if (fechaAsientoMoment.isAfter(maxDate)) {
                    $('#fecha_asiento').val(maxDate.format('YYYY/MM/DD'));
                }
            });

        })
        ();

        new TomSelect('#select-clientes', {
            valueField: 'CardCode',
            labelField: 'CardName',
            maxOptions: null,
            searchField: ['CardName'],
            onFocus: function() {
                this.load();
            },
            // prevent select empty value option
            onDropdownClose: function() {
                if (this.getValue() == '') {
                    this.clear();
                }
            },
            load: function(query, callback) {
                var self = this;
                if (self.loading > 1) {
                    callback();
                    return;
                }

                var url = '<?= route('provisiones.clientes') ?>';
                fetch(url)
                    .then(response => response.json())
                    .then(json => {
                        callback(json.options);
                        self.settings.load = null;
                    }).catch(() => {
                        callback();
                    });
            }
        });

        $('#filters').submit(function(event) {
            event.preventDefault();

            // toggle loading
            $('#loading-table').show();
            $('#btn-filter').prop('disabled', true);

            let fecha_inicial = $('#fecha_inicial').val();
            let fecha_final = $('#fecha_final').val();
            let CardCode = $('#select-clientes').val();

            // prepare url with params
            let url = '<?= route('provisiones.getProvisiones') ?>';
            url += '?fecha_inicial=' + fecha_inicial;
            url += '&fecha_final=' + fecha_final;
            url += '&CardCode=' + CardCode;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    dt_provisiones.clear().rows.add(data).draw();
                },
                error: function(xhr, status) {
                    dt_provisiones.clear().draw();
                    alert('Error comunicandonos con nuestra API, por favor intente mas tarde')
                },
                complete: function(xhr, status) {
                    // toggle loading
                    $('#loading-table').hide();
                    $('#btn-filter').prop('disabled', false);
                }
            });

        });

        dt_provisiones.on('select deselect', function(e, dt, type, indexes) {
            if (type === 'row') {
                let rows_selected = dt_provisiones.rows({
                    selected: true
                }).indexes().toArray();

                let rows_selected_data = dt_provisiones.rows(rows_selected).data().toArray();

                let total_flete = 0;
                let total_provision = 0;
                let total_valor_total = 0;

                rows_selected_data.forEach(row => {
                    total_flete += parseFloat(row.Flete);
                    total_provision += parseFloat(row.Provision);
                    total_valor_total += parseFloat(row['Valor Total']);
                });

                $('#text-total-flete').text(toPriceFormat(total_flete));
                $('#text-total-provision').text(toPriceFormat(total_provision));
                $('#text-total').text(toPriceFormat(total_valor_total));
            }
        });
    </script>
    @endPushOnce

</x-app-layout>