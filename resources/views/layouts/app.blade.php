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

    
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('theme/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/components.css') }}">
    
    <!-- CSS Libraries -->
    @stack('css')
    
    <style>
        .full-screen-loading {
            position: fixed;
            z-index: 1200;
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            cursor: wait;
        }

        .full-screen-loading-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 90%;
        }

        .sidebar-menu>li {
            border-bottom: 1px #edf1f3 solid;
            font-size: 1rem;
        }

        .main-sidebar .sidebar-menu li a {
            height: 40px;
        }

        body:not(.sidebar-mini) .sidebar-style-2 .sidebar-menu li.active ul.dropdown-menu li a {
            padding-left: 32px;
        }
    </style>

</head>

<body>

    <div class="full-screen-loading">
        <div class="full-screen-loading-inner">
            <img src="{{ asset('images/loading.gif') }}" height="100" alt="">
            <p class="my-0" id="fsl-t1">Procesando...</p>
            <p class="my-0" id="fsl-t2">Por favor no cierre esta ventana.</p>
        </div>
    </div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            @include('layouts.app.navbar')

            @include('layouts.app.sidebar')

            <div class="main-content">
                <section class="section">

                    @if (isset($header))
                        {{ $header }}
                    @endif

                    <div class="section-body">
                        {{ $slot }}
                    </div>

                </section>
            </div>

            @include('layouts.app.footer')

        </div>
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
    @stack('scripts')

    <!-- Template JS File -->
    <script src="{{ asset('theme/js/scripts.js?v=202309062336') }}"></script>

    <script>
        // makes sure modals is not hidden behind overlay
        $(function() {
            $('.modal').appendTo("body");
        });

        var FSL = {
            show: function() {
                $('.full-screen-loading').css('display', 'block');
                $('body').css('overflow', 'hidden');
            },
            hide: function() {
                $('.full-screen-loading').css('display', 'none');
                $('body').css('overflow', 'visible');
            }
        }
    </script>

</body>

</html>
