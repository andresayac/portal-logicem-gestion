<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Provisiones Log</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Log de provisiones enviadas</h4>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo Documento</th>
                                    <th>Usuario</th>
                                    <th>Código</th>
                                    <th>Petición</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents_log as $log)
                                    <tr>
                                        <td>{{ $log->created_at }}</td>
                                        <td style="max-width: 180px">{{ $log->document_type }}</td>
                                        <td>{{ $log->user->username }}</td>
                                        <td>
                                            @if ($log->response_code == 200)
                                                <span class="badge badge-success">{{ $log->response_code }}</span>
                                            @else
                                                <span title="{{ $log->response_message ?? $log->response_body }}"
                                                    class="badge badge-danger">{{ $log->response_code ?? 'Api Error' }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->request_body }}</td>
                                        <td>
                                            <div style="display: flex; column-gap: 10px;">
                                                <a href="{{ route('logs.details', $log->id) }}"
                                                    class="btn btn-sm btn-primary">Detalles</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $documents_log->links() }}
                    @if ($documents_log->total() > 0)
                        <div class="container text-center">
                            <div>
                                Mostrando del <?= $documents_log->firstItem() ?> a <?= $documents_log->lastItem() ?> de
                                <?= $documents_log->total() ?> en total
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
