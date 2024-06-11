<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <?php if (session()->has('login-required')) : ?>
    <div class="alert alert-danger">
        {{ session('login-required') }}
    </div>
    <?php endif ?>


    @if (!$is_admin)
        @if ('success')
            @if ($method === 'email' && $otp_generate)
                <div class="alert alert-success mt-2">OTP enviado verifica tu correo electrónico o el SPAM.</div>
            @elseif ($method === 'sms' && $otp_generate)
                <div class="alert alert-success mt-2">OTP enviado verifica tu celular.</div>
            @elseif (!$otp_generate)
                <div class="alert alert-danger mt-2">Espera 5 minutos para gererar otro OTP.</div>
            @endif
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
                @if ($is_admin)
                    <div class="form-group">
                        <label for="nit">Usuario</label>
                        <input id="nit" type="text" name="nit" value="{{ $nit }}" required
                            autofocus class="form-control" tabindex="1">
                        @error('nit')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password" required class="form-control"
                            tabindex="2">
                        @error('password')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                @elseif ($method === 'email')
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input id="email" type="email" name="email" value="{{ $email }}" required
                            autofocus class="form-control" tabindex="1" disabled readonly>
                        @error('email')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="otp">OTP: {{ $otp }}</label>
                        <input id="otp" type="text" name="otp" required class="form-control"
                            tabindex="2">
                        @error('otp')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                @elseif ($method === 'sms')
                    <div class="form-group">
                        <label for="mobile">Celular</label>
                        <input id="mobile" type="text" name="mobile" value="{{ $mobile }}" required
                            autofocus class="form-control" tabindex="1" disabled readonly>
                        @error('mobile')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="form-group mt-2">
                            {{-- <label for="otp">OTP: {{ $otp }}</label> --}}
                            <label for="otp">OTP: {{ $otp }}</label>
                            <input id="otp" type="text" name="otp" required class="form-control"
                                tabindex="2">
                            @error('otp')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                @endif

                <div class="form-group">
                    <button type="button" id="generate-otp" class="btn btn-primary btn-lg btn-block" tabindex="3" onclick=""
                        disabled>
                        Generar nuevo OTP
                    </button>
                    <div id="otp-timer" class="text-center mt-2"></div>
                </div>

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
            document.addEventListener('DOMContentLoaded', function() {
            const generateOtpButton = document.getElementById('generate-otp');
            const otpTimer = document.getElementById('otp-timer');

            const otpTimeGenerate = '{{ $otp_time_generate ?? '' }}';
            const otpCooldown = 5 * 60; // 5 minutos en segundos

            if (otpTimeGenerate) {
                const otpTimeGenerateDate = new Date(otpTimeGenerate).getTime() / 1000;
                const currentTime = Math.floor(Date.now() / 1000);
                const elapsedTime = currentTime - otpTimeGenerateDate;

                if (elapsedTime >= otpCooldown) {
                    generateOtpButton.removeAttribute('disabled');
                    otpTimer.textContent = 'Puede generar un nuevo OTP';
                } else {
                    let remainingTime = otpCooldown - elapsedTime;
                    const interval = setInterval(function() {
                        const minutes = Math.floor(remainingTime / 60);
                        const seconds = remainingTime % 60;
                        otpTimer.textContent = `Nuevo OTP en ${minutes} min y ${seconds < 10 ? '0' : ''}${seconds} segundos para reintentar`;
                        if (remainingTime <= 0) {
                            clearInterval(interval);
                            generateOtpButton.removeAttribute('disabled');
                            otpTimer.textContent = 'Puede generar un nuevo OTP';
                        } else {
                            remainingTime--;
                        }
                    }, 1000);
                }
            }

            // funcion para actualizar la pagina y generar un nuevo OTP
            generateOtpButton.addEventListener('click', function() {
                window.location.reload();
            });
        });
        </script>
    @endPushOnce

</x-guest-layout>
