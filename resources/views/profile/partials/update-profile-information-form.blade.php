<div class="card">
    <div class="card-header pb-1" style="min-height: unset;">
        <h4>Información de perfil</h4>
    </div>

    <div class="card-body pt-1">

        <p>Actualice la información de perfil y la dirección de correo electrónico de su cuenta.</p>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-6">
                    <input required type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                    <?php if ($errors->has('name')) : ?>
                        @foreach ($errors->get('name') as $error)
                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                        @endforeach
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input required type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                    <?php if ($errors->has('email')) : ?>
                        @foreach ($errors->get('email') as $error)
                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                        @endforeach
                    <?php endif ?>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                @if (session('status') === 'profile-updated')
                <span class="badge badge-success ml-2">Guardado</span>
                @endif
            </div>
        </form>

    </div>
</div>