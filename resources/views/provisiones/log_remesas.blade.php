<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Remesas Log</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-1" style="min-height: unset;">
                    <h4>Remesas enviadas</h4>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table w-50 table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Remesas</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($log->remesas as $remesa)
                                            <tr>
                                                <td>{{ $remesa }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-2">
                        <div class="col-12">
                            <a href="{{ url()->previous() }}" class="btn btn-primary">Volver</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>

    @pushOnce('css')
    @endPushOnce

    @pushOnce('scripts')
    @endPushOnce

</x-app-layout>
