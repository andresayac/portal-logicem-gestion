<x-app-layout>

    <style>
        .remesas-card {
            max-height: 300px;
            overflow: auto;
        }
    </style>

    <x-slot name="header">
        <div class="section-header">
            <h1>Detalles del Log</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body text-center">
                    <span>Código de respuesta </span>
                    @if ($log->response_code == 204)
                    <span class="badge badge-success"><?= $log->response_code ?></span>
                    @else
                    <span class="badge badge-danger"><?= $log->response_code ?? 'Api Error' ?></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body text-center remesas-card">
                    <div><span>Manifiesto: <?= $log->manifiesto ?> </span></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body text-center">
                    <div><span>Enviado el <?= $log->created_at ?> </span></div>
                    <div><span>Lote <?= $log->lot ?> </span></div>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($log->request_body))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Cuerpo de la petición</h4>
                </div>
                <div class="card-body">
                    <pre style="max-height: 300px"><?= json_encode(json_decode($log->request_body), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></pre>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (!empty($log->response_body))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Cuerpo de la respuesta</h4>
                </div>
                <div class="card-body">
                    <pre style="max-height: 300px"><?= json_encode(json_decode($log->response_body), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></pre>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (!empty($log->response_message))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Mensaje de respuesta</h4>
                </div>
                <div class="card-body">
                    <div><?= $log->response_message ?></div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @pushOnce('css')
    @endPushOnce

    @pushOnce('scripts')
    @endPushOnce

</x-app-layout>