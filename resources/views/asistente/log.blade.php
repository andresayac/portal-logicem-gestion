<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Asistente Log</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Log de manifiestos enviados</h4>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Lote</th>
                                    <th>Manifiesto</th>
                                    <th>Usuario</th>
                                    <th>Código</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistente_logs as $log)
                                <tr>
                                    <td><?= $log->created_at ?></td>
                                    <td>...<?= substr($log->lot, -5) ?></td>
                                    <td><?= $log->manifiesto ?></td>
                                    <td><?= $log->user->username ?></td>
                                    <td>
                                        @if ($log->response_code == 204)
                                        <span class="badge badge-success"><?= $log->response_code ?></span>
                                        @else
                                        <span title="<?= $log->response_message ?? $log->response_body ?>" class="badge badge-danger"><?= $log->response_code ?? 'Api Error' ?></span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; column-gap: 10px;">
                                            <a href="<?= route('asistente.log.details', $log->id) ?>" class="btn btn-sm btn-primary">Detalles</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <?= $asistente_logs->links() ?>
                    @if($asistente_logs->total() > 0)
                    <div class="container">
                        <div>
                            Mostrando del <?= $asistente_logs->firstItem() ?> a <?= $asistente_logs->lastItem() ?> de <?= $asistente_logs->total() ?> en total
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @pushOnce('css')
    @endPushOnce

    @pushOnce('scripts')
    @endPushOnce

</x-app-layout>