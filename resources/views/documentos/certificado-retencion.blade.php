<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Certificado de retenciones</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card" id="whatsapp-support" style="display: none;">
                <div class="card-header pb-1" style="min-height: unset;">
                    <p>Actualmente, tenemos un problema para generar tu solicitud. Por favor, contáctanos por WhatsApp
                        para solucionarlo.</p>
                </div>
                <div class="card-body pt-1">
                    <div class="container m-1 text-center">
                        <a href="https://wa.me/573058363083?text=Hola" target="_blank" rel="noopener noreferrer"
                            class="btn btn-success">
                            <i class="fab fa-whatsapp"></i> Contacto via Whatsapp
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Filtros de búsqueda</h4>
                </div>
                <div class="card-body pt-1">
                    <form id="filters" class="row">
                        <div class='col-6'>
                            <div class="form-group">
                                <label>Tipo certificado <code>*</code></label>
                                <select class="form-control" id="type_certificate">
                                    @foreach ($type_certificates as $key => $type_certificate)
                                        <option value="{{ $key }}">{{ $type_certificate }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class='col-6' id="year-container" style="display:none;">
                            <div class="form-group">
                                <label>Año de certificado <code>*</code></label>
                                <select class="form-control" id="year_certificate" disabled>
                                    @foreach ($list_years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class='col-6' id="month-range" style="display:none;">
                            <div class="form-group">
                                <label>Mes Desde <code>*</code></label>
                                <select class="form-control" id="month_from">
                                    @foreach ($months as $key => $month)
                                        <option value="{{ $key }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Mes Hasta <code>*</code></label>
                                <select class="form-control" id="month_to">
                                    @foreach ($months as $key => $month)
                                        <option value="{{ $key }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class='col-12'>
                            <div class="mt-3">
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

        <div id="is-generated" class="d-none">
            <div class="col-md-6 col-lg-6 col-sm-12 d-flex justify-content-center">
                <div class="card w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="row">
                            <div class="col-12 text-center mt-3">
                                <h5>Certificado generado</h5>
                            </div>

                            <div class="col-12 d-flex justify-content-center flex-wrap buttons">

                                <button id="btn-view" class="btn btn-primary mb-2" onclick="visualizarPDF()">
                                    <i class="fas fa-eye"></i>
                                    <span class="d-none d-sm-inline">Visualizar</span>
                                </button>
                                <button id="btn-download" class="btn btn-primary mb-2" onclick="descargarPDF()">
                                    <i class="fas fa-save"></i>
                                    <span class="d-none d-sm-inline">Descargar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12 col-lg-12 col-sm-12" id="show-pdf" style="display: none;">
            <div class="card">
                <div class="card-header">
                    <h4>Documento generado</h4>
                    <div class="card-header-action">
                        <button id="btn-download" class="btn btn-primary" onclick="descargarPDF()">
                            <i class="fas fa-save"></i>
                            <span class="d-none d-sm-inline">Descargar</span>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-1">
                    <div class="row mt-3">
                    </div>
                    <embed id='embed-pdf' type="application/pdf" width="100%" height="600px" />
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
            let pdfBase64 = '';

            $(document).ready(function() {
                $("#whatsapp-support").hide();
                $('#show-pdf').hide();
                $('#btn-filter').prop('disabled', true);

                // Controlar el tipo de certificado seleccionado
                $('#type_certificate').change(function() {
                    let type_certificate = $(this).val();
                    let current_year = new Date().getFullYear();

                    $('#year-container').show();

                    if (type_certificate == '4') { // FUENTE
                        $('#year_certificate').prop('disabled', false).html('<option value="' + (current_year -
                            1) + '">' + (current_year - 1) + '</option>');
                        $('#month-range').hide();
                        $('#show-pdf').hide();
                        $('#btn-filter').prop('disabled', false);
                    } else if (type_certificate == '1') { // ICA
                        $('#year_certificate').prop('disabled', false).html('<option value="' + current_year +
                            '">' + current_year + '</option><option value="' + (current_year - 1) + '">' + (
                                current_year - 1) + '</option>');
                        $('#month-range').show();
                        $('#show-pdf').hide();
                        $('#btn-filter').prop('disabled', false);
                    } else {
                        //year_certificate
                        $('#show-pdf').hide();
                        $('#year_certificate').prop('disabled', true).html('');
                        $('#month-range').hide();
                        // btn-filter disabled
                        $('#btn-filter').prop('disabled', true);
                    }
                });
            });

            $('#filters').submit(function(event) {
                event.preventDefault();

                let year_certificate = Number($('#year_certificate').val());
                let type_certificate = $('#type_certificate').val();
                let month_from = $('#month_from').val();
                let month_to = $('#month_to').val();

                if (!year_certificate || !type_certificate) {
                    alert('Por favor complete los campos requeridos');
                    return;
                }
                if (type_certificate == '') {
                    alert('Por favor seleccione un tipo de certificado');
                    return;
                }

                $('#loading-table').show();
                $('#show-pdf').hide();
                $('#btn-filter').addClass('disabled btn-progress');

                let year_current = new Date().getFullYear();
                if (year_certificate < year_current - 1 || year_certificate > year_current) {
                    alert('El año del certificado debe estar entre ' + (year_current - 1) + ' y ' + year_current);
                    $('#btn-filter').removeClass('disabled btn-progress');
                    $('#loading-table').hide();
                    return;
                }

                // prepare url with params
                let url = '<?= route('documentos.certificado-retenciones-pdf') ?>';
                url += '?year_certificate=' + year_certificate;
                url += '&type_certificate=' + type_certificate;
                if (type_certificate == '1') { // ICA
                    url += '&month_from=' + month_from + '&month_to=' + month_to;
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {

                        if (data.status) {
                            $("#whatsapp-support").hide();
                            if (!data.validacion) {
                                $('#btn-filter').removeClass('disabled btn-progress');
                                $('#show-pdf').hide();
                                $("#whatsapp-support").show();
                                alert(data.message)
                                return;
                            }
                            $('#show-pdf').show();
                            $('#btn-filter').removeClass('disabled btn-progress');
                            $('#embed-pdf').attr('src', 'data:application/pdf;base64,' + data.pdf);
                            pdfBase64 = data.pdf;
                        } else {
                            $('#whatsapp-support').show();
                            $('#show-pdf').hide();
                            alert('Error generando el certificado, por favor intente mas tarde')
                        }
                    },
                    error: function(xhr, status) {
                        $('#btn-filter').removeClass('disabled btn-progress');
                        $('#show-table').hide();
                        $("#whatsapp-support").show();

                        const message = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON
                            .message : 'Error comunicandonos con nuestra API, por favor intente mas tarde';

                        alert(message)
                    },
                    complete: function(xhr, status) {
                        $('#loading-table').hide();
                    }
                });

            });

            function descargarPDF() {
                var blob = base64toBlob(pdfBase64, 'application/pdf');
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                const name_pdf = 'certificado_retencion_' + new Date().getTime() + '.pdf';
                a.href = url;
                a.download = name_pdf;
                a.click();
                URL.revokeObjectURL(url);
            }

            function base64toBlob(base64Data, contentType) {
                var sliceSize = 512;
                var byteCharacters = atob(base64Data);
                var byteArrays = [];

                for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                    var slice = byteCharacters.slice(offset, offset + sliceSize);
                    var byteNumbers = new Array(slice.length);
                    for (var i = 0; i < slice.length; i++) {
                        byteNumbers[i] = slice.charCodeAt(i);
                    }
                    var byteArray = new Uint8Array(byteNumbers);
                    byteArrays.push(byteArray);
                }

                var blob = new Blob(byteArrays, {
                    type: contentType
                });
                return blob;
            }

            // init year_certificate max year current - 1
            $('#year_certificate').attr('max', new Date().getFullYear());
        </script>
    @endPushOnce

</x-app-layout>
