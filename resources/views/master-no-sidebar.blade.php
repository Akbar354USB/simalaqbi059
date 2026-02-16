<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>Simalaqbi059</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- [Favicon] icon -->
    <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon"> <!-- [Google Font] Family -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('backend/mantis/assets/fonts/tabler-icons.min.css') }}">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('backend/mantis/assets/fonts/feather.css') }}">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('backend/mantis/assets/fonts/fontawesome.css') }}">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('backend/mantis/assets/fonts/material.css') }}">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('backend/mantis/assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('backend/mantis/assets/css/style-preset.css') }}">
    @yield('css')
    <style>
        /* Hilangkan ruang sidebar untuk semua elemen */
        .pc-container.no-sidebar,
        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }

        /* Topbar (navbar) full width */
        .navbar,
        .navbar-bg,
        .pc-header {
            margin-left: 0 !important;
            left: 0 !important;
            width: 100% !important;
        }

        /* Footer full width */
        .pc-footer,
        .footer {
            margin-left: 0 !important;
            left: 0 !important;
            width: 100% !important;
        }
    </style>

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- sidebar -->
    {{-- @include('backend.partial.sidebar') --}}


    <!-- topbar -->
    @include('backend.partial.topbar')


    <!-- [ Main Content ] start -->
    <div class="pc-container no-sidebar">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->


    <!-- footer -->
    @include('backend.partial.footer')


    <!-- Required Js -->
    <script src="{{ asset('backend/mantis/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('backend/mantis/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/mantis/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/mantis/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('backend/mantis/assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('backend/mantis/assets/js/plugins/feather.min.js') }}"></script>
    <script>
        layout_change('light');
    </script>
    <script>
        change_box_container('false');
    </script>
    <script>
        layout_rtl_change('false');
    </script>
    <script>
        preset_change("preset-1");
    </script>
    <script>
        font_change("Public-Sans");
    </script>

    <!-- General JS Scripts -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('{{ asset('sw.js') }}')
                    .then(function(registration) {
                        console.log('Service Worker registered with scope:', registration.scope);
                    })
                    .catch(function(error) {
                        console.error('Service Worker registration failed:', error);
                    });
            });
        }
    </script>

    <!-- Page Specific JS File -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('js')
</body>
<!-- [Body] end -->

</html>
