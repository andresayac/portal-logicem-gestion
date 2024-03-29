<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <?php if (session()->has('login-required')) : ?>
    <div class="alert alert-danger">
        {{ session('login-required') }}
    </div>
    <?php endif ?>

    <div class="login-brand mb-1">
        <img src="{{ asset('images/logoF.png') }}" alt="logo" width="250" class="">
    </div>

    <div class="mb-3 text-center">
        <h4>Portal Interno</h4>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4>
                Inicio de sesión
            </h4>
        </div>

        <div class="card-body">

            <form method="POST" id="form-login" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Usuario</label>
                    <input id="email" type="text" name="username" value="{{ old('username') }}" required
                        autofocus class="form-control" tabindex="1">
                    <?php if ($errors->has('username')) : ?>
                    @foreach ($errors->get('username') as $error)
                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                    @endforeach
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Contraseña</label>
                    </div>
                    <input id="password" type="password" name="password" class="form-control" tabindex="2" required>
                    <?php if ($errors->has('password')) : ?>
                    @foreach ($errors->get('password') as $error)
                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                    @endforeach
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        Iniciar sesión
                    </button>
                </div>
            </form>

        </div>
    </div>
    <div class="simple-footer">
        Copyright &copy; Logicem <?= date('Y') ?>
    </div>

    @pushOnce('scripts')
        <script>
            $('#form-login').on('submit', function() {
                console.log($(this).find('button[type="submit"]'));
                $(this).find('button[type="submit"]').attr('disabled', true);
                $(this).find('button[type="submit"]').addClass('btn-progress');
            });
        </script>
    @endPushOnce


</x-guest-layout>
