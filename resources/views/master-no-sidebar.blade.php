<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'Simalaqbi058')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/stisla/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/stisla/assets/modules/fontawesome/css/all.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('backend/stisla/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/stisla/assets/css/components.css') }}">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>

    @yield('css')

    <style>
        /* HILANGKAN RUANG SIDEBAR */
        .main-content {
            margin-left: 0 !important;
            padding-left: 30px;
            padding-right: 30px;
        }

        .navbar {
            left: 0 !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">

            <div class="navbar-bg"></div>

            {{-- TOPBAR --}}
            @include('backend.partial.topbar')

            <!-- MAIN CONTENT -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                    </div>

                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
            </div>

            {{-- FOOTER --}}
            @include('backend.partial.footer')

        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('backend/stisla/assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/js/stisla.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('backend/stisla/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/js/custom.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('js')
</body>

</html>
