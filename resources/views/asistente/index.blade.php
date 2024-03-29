<x-app-layout>

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
    </style>

    <x-slot name="header">
        <div class="section-header">
            <h1>Asistente</h1>
        </div>
    </x-slot>

    <div class="row">

        <div class="col-6">

            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Proveedor</h4>
                </div>

                <div class="card-body pt-1">

                    <form id="filters">

                        <div class="form-group mb-2">
                            <label>Proveedor <code> *</code></label>
                            <select class="form-control form-control-sm" id="select-proveedores" name="proveedor">
                                <option value="">Seleccione un proveedor</option>
                            </select>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-right">
                                <button id="btn-filter" type="submit" class="btn btn-primary btn-icon icon-left">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
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

                    <form action="#" id="procesar">

                        <div class="form-group mb-2">
                            <label>Factura <code>*</code></label>
                            <input required type="text" class="form-control form-control-sm" id="input-factura" name="factura">
                        </div>

                        <div class="form-group mb-2">
                            <label>Fecha <code>*</code></label>
                            <input required type="text" class="form-control form-control-sm" id="input-fecha" name="fecha">
                        </div>

                        <div class="form-group mb-2">
                            <label>Comentario</label>
                            <textarea class="form-control form-control-sm" id="U_U_Comen_Fact" name="U_U_Comen_Fact" rows="2"></textarea>
                        </div>

                        <div class="mt-3 text-right">
                            <button type="submit" class="btn btn-primary btn-icon icon-left">
                                <i class="fas fa-vote-yea"></i> Procesar
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card card-primary card-totals-provisiones">
                <div class="card-header">
                    <h4>Total Flete Seleccionados</h4>
                </div>
                <div class="card-body">
                    <h6 id="text-total-flete">$ 0</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Manifiestos</h4>
                </div>

                <div class="card-body pt-1">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table_result" style="width: 100%;"></table>
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

        table.dataTable tbody>tr.selected {
            background-color: #ff8e63;
        }

        div.dataTables_wrapper div.dataTables_processing {
            top: 50px !important;
            width: 62px !important;
            height: 62px !important;
            border: 1px solid black !important;
        }
    </style>
    @endpushOnce

    @pushOnce('scripts')
    <script src="//cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="<?= asset('theme/modules/bootstrap-daterangepicker/daterangepicker.js') ?>"></script>
    <script src="<?= asset('theme/modules/datatables/datatables.min.js') ?>"></script>
    <script src="<?= asset('theme/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= asset('theme/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') ?>"></script>
    <script src="<?= asset('theme/modules/jquery-datatables-checkboxes-1.2.12/js/dataTables.checkboxes.min.js') ?>"></script>
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

        new TomSelect('#select-proveedores', {
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

                var url = '<?= route('asistente.proveedores') ?>';
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

        $('#input-fecha').daterangepicker({
            singleDatePicker: true,
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
            }
        });

        var datatable_1 = $('#table_result').DataTable({
            scrollX: true,
            paging: false,
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
            processing: true,
            serverSide: false,
            columnDefs: [{
                'targets': 0,
                'checkboxes': {
                    'selectRow': true
                }
            }],
            select: {
                style: 'multi',
            },
            columns: [{
                    data: "index",
                    title: "index",
                },
                {
                    data: "Manifiesto",
                    title: "Manifiesto"
                },
                {
                    data: "Placa",
                    title: "Placa"
                },
                {
                    data: "Fecha",
                    title: "Fecha",
                    render: function(data, type, row) {
                        if (data)
                            return data.replace('00:00:00.000000000', '');
                        return '';
                    }
                },
                {
                    data: "Flete",
                    title: "Flete",
                    render: val => toPriceFormat(val),
                },
                {
                    data: "Origen",
                    title: "Origen"
                },
                {
                    data: "Destino",
                    title: "Destino"
                },
            ],
        });

        $('#table_result').on('xhr.dt', function() {
            document.getElementById('btn-filter').removeAttribute('disabled');

        });

        document.getElementById('filters').addEventListener('submit', function(e) {
            e.preventDefault();

            var cardcode = document.getElementById('select-proveedores').value;


            if (cardcode == '') {
                alert('Seleccione un proveedor');
                return;
            }

            document.getElementById('btn-filter').setAttribute('disabled', 'disabled');

            datatable_1.ajax.url("<?= route('asistente.manifiestos') ?>" + '?cardcode=' + cardcode);
            datatable_1.ajax.reload();

        });

        $('#procesar').submit(function(e) {
            e.preventDefault();

            let rows_selected = datatable_1.column(0).checkboxes.selected();

            if (rows_selected.length === 0)
                return alert('Debe seleccionar al menos un registro de la tabla.');

            FSL.show();

            let rows_selected_data = [];

            $.each(rows_selected, function(index, rowId) {
                selected_row_index = rowId;
                let data = datatable_1.row(selected_row_index).data();
                rows_selected_data.push(data);
            });

            console.log(rows_selected_data);

            $.ajax({
                url: "<?= route('asistente.sendManifiestos') ?>",
                type: 'POST',
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({
                    manifiestos: rows_selected_data,
                    factura: $('#input-factura').val(),
                    fecha: $('#input-fecha').val(),
                    U_U_Comen_Fact: $('#U_U_Comen_Fact').val()
                }),
                headers: {
                    'X-CSRF-TOKEN': "<?= csrf_token() ?>"
                },
                success: function(data) {
                    window.location.href = "<?= route('asistente.log') ?>";
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

        datatable_1.on('select deselect', function(e, dt, type, indexes) {

            if (type === 'row') {
                let rows_selected = datatable_1.rows({
                    selected: true
                }).indexes().toArray();

                let rows_selected_data = datatable_1.rows(rows_selected).data().toArray();

                let total_flete = 0;

                rows_selected_data.forEach(row => {
                    total_flete += parseFloat(row.Flete);
                });

                $('#text-total-flete').text(toPriceFormat(total_flete));
            }
        });
    </script>
    @endpushOnce

</x-app-layout>