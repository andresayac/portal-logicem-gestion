<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Inicio</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    Bienvenido <b>@if (Auth::user()) {{ Auth::user()->name }} @endif </b>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
