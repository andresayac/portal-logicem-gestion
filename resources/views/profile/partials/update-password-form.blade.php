<div class="card">
    <div class="card-header pb-1" style="min-height: unset;">
        <h4>Actualizar contraseña</h4>
    </div>

    <div class="card-body pt-1">

        <p>Asegúrese de que su cuenta utiliza una contraseña larga y aleatoria para mantener la seguridad.</p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-2 col-form-label">Contraseña actual</label>
                <div class="col-sm-6">
                    <input required type="password" class="form-control" id="current_password" name="current_password">
                    <?php if ($errors->updatePassword->has('current_password')) : ?>
                        @foreach ($errors->updatePassword->get('current_password') as $error)
                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                        @endforeach
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-2 col-form-label">Nueva contraseña</label>
                <div class="col-sm-6">
                    <input required type="password" class="form-control" id="password" name="password">
                    <?php if ($errors->updatePassword->has('password')) : ?>
                        @foreach ($errors->updatePassword->get('password') as $error)
                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                        @endforeach
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row align-items-center mb-3">
                <label class="col-sm-2 col-form-label">Confirmar contraseña</label>
                <div class="col-sm-6">
                    <input required type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    <?php if ($errors->updatePassword->has('password_confirmation')) : ?>
                        @foreach ($errors->updatePassword->get('password_confirmation') as $error)
                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                        @endforeach
                    <?php endif ?>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                @if (session('status') === 'password-updated')
                <span class="badge badge-success ml-2">Guardado</span>
                @endif
            </div>

        </form>

    </div>
</div>