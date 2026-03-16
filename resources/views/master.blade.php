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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    @yield('css')

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
    @include('backend.partial.sidebar')


    <!-- topbar -->
    @include('backend.partial.topbar')


    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- footer -->
    @include('backend.partial.footer')


    <!-- Required Js -->
    <!-- jQuery (WAJIB PALING ATAS) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    @yield('js')
    <script>
        setInterval(function() {
            fetch("{{ route('refresh.session') }}")
                .then(response => response.json())
                .then(data => {
                    console.log("Session diperbarui");
                })
                .catch(error => console.log("Session refresh gagal"));
        }, 300000); // 5 menit
    </script>
</body>
<!-- [Body] end -->

</html>
