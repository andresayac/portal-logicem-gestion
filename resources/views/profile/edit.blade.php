<x-app-layout>

    <x-slot name="header">
        <div class="section-header">
            <h1>Perfil</h1>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</x-app-layout>