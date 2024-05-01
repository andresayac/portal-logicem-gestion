<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Certificado de retenciones</h1>
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
                                <label>Año de certificado <code>*</code></label>
                                <input required type="number" min='2005' max='2023' class="form-control"
                                    id="year_certificate" name="<h1>Certificado de retenciones</h1>">
                            </div>
                        </div>
                        <div class='col-6'>
                            <div class="form-group">
                                <label>Tipo certificado <code>*</code></label>
                                <select class="form-control" id="type_certificate">
                                    <option value="" selected></option>
                                    <option value="0">AUTORETENCION</option>
                                    <option value="1">ICA</option>
                                    <option value="2">IVA</option>
                                    <option value="3">TIMBRE</option>
                                    <option value="4">FUENTE</option>
                                    <option value="5">CREE</option>
                                    <option value="6">SOBRETASA-ICA</option>

                                </select>
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


        <div class="col-md-12 col-lg-12 col-sm-12 d-none" id="show-pdf">
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

            // click visualizarPDF button show  show-pdf if is show hidden
            function visualizarPDF() {
                $('#show-pdf').removeClass('d-none');
            }

            $('#filters').submit(function(event) {
                event.preventDefault();

                let year_certificate = $('#year_certificate').val();
                let type_certificate = $('#type_certificate').val();

                if (!year_certificate || !type_certificate) {
                    alert('Por favor complete los campos requeridos');
                    return;
                }

                // if type_certificate = '' show alert
                if (type_certificate == '') {
                    alert('Por favor seleccione un tipo de certificado');
                    return;
                }

                // year certificate must be between 2005 and year current - 1 year
                let year_current = new Date().getFullYear();
                if (year_certificate < 2005 || year_certificate > year_current - 1) {
                    alert('El año del certificado debe estar entre 2005 y ' + (year_current - 1));
                    return;
                }

                // prepare url with params
                let url = '<?= route('documentos.certificado-retenciones-pdf') ?>';
                url += '?year_certificate=' + year_certificate;
                url += '&type_certificate=' + type_certificate;

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data)
                        if (data.status) {
                            $('#embed-pdf').attr('src', 'data:application/pdf;base64,' + data.pdf);
                            pdfBase64 = data.pdf;
                            visualizarPDF();
                        } else {
                            alert('Error generando el certificado, por favor intente mas tarde')
                        }
                    },
                    error: function(xhr, status) {
                        alert('Error comunicandonos con nuestra API, por favor intente mas tarde')
                    },
                    complete: function(xhr, status) {

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
        </script>
    @endPushOnce

</x-app-layout>