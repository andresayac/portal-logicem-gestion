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
            <form method="POST" id="form-login" action="{{ route('otp') }}">
                @csrf

                @if (!$is_valid_phone && !$is_valid_email)
                    <div class="alert alert-danger">
                        No hay ningún método disponible para el inicio de sesión.
                    </div>
                @else
                    <div class="form-group">
                        <label for="method">Método de Verificación</label>
                        <select id="method" name="method" class="form-control" required>
                            @if ($is_valid_email)
                                <option value="email">Correo Electrónico</option>
                            @endif
                            @if ($is_valid_phone)
                                <option value="sms">SMS</option>
                            @endif
                        </select>
                    </div>

                    <div id="email-section" style="display: none;">
                        <div class="form-group">
                            <label for="nit">NIT</label>
                            <input id="nit" type="text" name="nit" value="{{ $nit }}" required
                                autofocus class="form-control" tabindex="1" readonly>
                            @error('nit')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="mail">Correo Electrónico</label>
                            <input id="email_address" type="text" name="email_address" value="{{ $email_address }}"
                                required autofocus class="form-control" tabindex="1" disabled readonly>
                            @error('email_address')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email_address_confirm">Confirme correo Electrónico</label>
                            <input id="email_address_confirm" type="email" name="email_address_confirm"
                                class="form-control" tabindex="2">
                            @error('email_address_confirm')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="sms-section" style="display: none;">
                        <div class="form-group">
                            <label for="nit">NIT</label>
                            <input id="nit" type="text" name="nit" value="{{ $nit }}" required
                                autofocus class="form-control" tabindex="1" readonly>
                            @error('nit')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="cellular">Número de Celular</label>
                            <input id="cellular" type="text" name="cellular" value="{{ $cellular }}" required
                                autofocus class="form-control" tabindex="1" disabled readonly>
                            @error('cellular')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="cellular_confirm">Confirme número de Celular</label>
                            <input id="cellular_confirm" type="text" name="cellular_confirm" class="form-control"
                                tabindex="2">
                            @error('cellular_confirm')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
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
            document.addEventListener('DOMContentLoaded', function() {
                const methodSelect = document.getElementById('method');
                const emailSection = document.getElementById('email-section');
                const smsSection = document.getElementById('sms-section');
                const emailConfirm = document.getElementById('email_address_confirm');
                const cellularConfirm = document.getElementById('cellular_confirm');



                methodSelect.addEventListener('change', function() {
                    const selectedMethod = methodSelect.value;

                    if (selectedMethod === 'email') {
                        emailSection.style.display = 'block';
                        smsSection.style.display = 'none';
                        emailConfirm.setAttribute('required', true);
                        cellularConfirm.removeAttribute('required');
                    } else if (selectedMethod === 'sms') {
                        emailSection.style.display = 'none';
                        smsSection.style.display = 'block';
                        emailConfirm.removeAttribute('required');
                        cellularConfirm.setAttribute('required', true);
                    }
                });



                // Trigger change event to set initial state
                methodSelect.dispatchEvent(new Event('change'));

                document.getElementById('form-login').addEventListener('submit', function() {
                    const submitButton = this.querySelector('button[type="submit"]');
                    submitButton.setAttribute('disabled', true);
                    submitButton.classList.add('btn-progress');
                });
            });
        </script>
    @endPushOnce

</x-guest-layout>
