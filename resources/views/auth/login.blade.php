<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <?php if (session()->has('login-required')) : ?>
    <div class="alert alert-danger">
        {{ session('login-required') }}
    </div>
    <?php endif ?>

    @if ($errors->has('error'))
        @foreach ($errors->get('error') as $error)
            <div class="alert alert-danger mt-2">{{ $error }}</div>
        @endforeach
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
            <form method="POST" id="form-login" action="{{ route('check') }}">
                @csrf

                <div class="form-group">
                    <label for="text">NIT</label>
                    <input id="email" type="text" name="nit" value="{{ old('nit') ?? '901643748-8' }}"
                        required autofocus class="form-control" tabindex="1">
                    <?php if ($errors->has('nit')) : ?>
                    @foreach ($errors->get('nit') as $error)
                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                    @endforeach
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        Continuar
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
