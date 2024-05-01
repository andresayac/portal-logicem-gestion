<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <?php if (session()->has('login-required')) : ?>
    <div class="alert alert-danger">
        {{ session('login-required') }}
    </div>
    <?php endif ?>


    @if (!$is_admin)
        @if ('success')
            <div class="alert alert-success mt-2">OTP enviado verifica tu bandera de entrada o el SPAM.</div>
        @else
            <div class="alert alert-danger mt-2">Error al enviar el OTP</div>
        @endif
    @endif



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


                @if($is_admin)
                    <div class="form-group">
                        <label for="nit">Usuario</label>
                        <input id="nit" type="text" name="nit" value="{{ $nit }}" required
                            autofocus class="form-control" tabindex="1">
                        @error('nit')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password" required class="form-control"
                            tabindex="2">
                        @error('password')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input id="email" type="email" name="email" value="{{ $email }}" required
                            autofocus class="form-control" tabindex="1" disabled readonly>
                        @error('email')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="otp">OTP: @php echo $otp; @endphp</label>
                        <input id="otp" type="text" name="otp" required class="form-control"
                            tabindex="2">
                        @error('otp')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="3">
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