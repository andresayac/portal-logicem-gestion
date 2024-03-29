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
                                    <th>Remesas</th>
                                    <th>Usuario</th>
                                    <th>código</th>
                                    <th>Registro SAP</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($provisiones_log as $log)
                                <tr>
                                    <td>{{ $log->created_at }}</td>
                                    <td style="max-width: 180px">{{ \Illuminate\Support\Str::limit(implode(', ', $log->remesas), 146, $end='...') }}</td>
                                    <td>{{ $log->user->username }}</td>
                                    <td>
                                        @if ($log->response_code == 201)
                                        <span class="badge badge-success">{{ $log->response_code }}</span>
                                        @else
                                        <span title="{{ $log->response_message ?? $log->response_body }}" class="badge badge-danger">{{ $log->response_code ?? 'Api Error' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->JdtNum }}</td>
                                    <td>
                                        <div style="display: flex; column-gap: 10px;">
                                            <a href="{{ route('provisiones.log.details', $log->id) }}" class="btn btn-sm btn-primary">Detalles</a>
                                            <a href="{{ route('provisiones.log.remesas', $log->id) }}" class="btn btn-sm btn-warning">Remesas</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $provisiones_log->links() }}
                    @if($provisiones_log->total() > 0)
                    <div class="container">
                        <div>
                            Mostrando del <?= $provisiones_log->firstItem() ?> a <?= $provisiones_log->lastItem() ?> de <?= $provisiones_log->total() ?> en total
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