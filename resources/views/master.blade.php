<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Simalaqbi058</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/stisla/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/stisla/assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('backend/stisla/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/stisla/assets/css/components.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @yield('css')
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>

            {{-- topbar --}}
            @include('backend.partial.topbar')

            {{-- sidebar --}}
            @include('backend.partial.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                    </div>

                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
            </div>
            {{-- footer --}}
            @include('backend.partial.footer')
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('backend/stisla/assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/modules/popper.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/js/stisla.js') }}"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template JS File -->
    <script src="{{ asset('backend/stisla/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('backend/stisla/assets/js/custom.js') }}"></script>
    @yield('js')
</body>

</html>
