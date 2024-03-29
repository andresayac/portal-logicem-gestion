<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('images/favicon.png') }}" sizes="32x32" />
    <link rel="icon" href="{{ asset('images/favicon.png') }}" sizes="192x192" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}" />
    <meta name="msapplication-TileImage" content="{{ asset('images/favicon.png') }}" />

    <title>{{ config('app.name', 'Logicem') }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('theme/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('theme/css/style.css') }}">

</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('theme/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/modules/popper.js') }}"></script>
    <script src="{{ asset('theme/modules/tooltip.js') }}"></script>
    <script src="{{ asset('theme/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('theme/modules/moment.min.js') }}"></script>
    <script src="{{ asset('theme/js/stisla.js') }}"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="{{ asset('theme/js/scripts.js') }}"></script>

    @stack('scripts')

</body>

</html>
