<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>Simalaqbi - 059</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('backend/landing/css/flaticon.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('backend/landing/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/landing/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/landing/css/custom.css') }}">
    <style>
        /* tombol kanan atas */

        .hero-policy-top {
            position: absolute;
            top: 25px;
            right: 40px;
            z-index: 20;
        }

        /* style tombol */

        .btn-privacy {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            backdrop-filter: blur(6px);
            transition: all .25s ease;
        }

        /* icon */

        .btn-privacy i {
            margin-right: 6px;
        }

        /* hover */

        .btn-privacy:hover {
            background: white;
            color: #0d6efd;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .icon i {
            font-size: 35px;
            color: #174ecc;
        }

        /* mobile */

        @media(max-width:768px) {

            .hero-policy-top {
                right: 15px;
                top: 15px;
            }

            .btn-privacy {
                font-size: 12px;
                padding: 6px 12px;
            }

        }
    </style>
</head>

<body>
    <!-- END nav -->

    <div class="hero-wrap"
        style="background-image: url('https://simalaqbi059.id/backend/landing/backgroundlanding.png'); background-attachment:fixed;">
        <div class="hero-logo-top">
            <img src="https://simalaqbi059.id/backend/simalaqbi.png" alt="Logo SIMALAQBI">
        </div>
        <div class="hero-policy-top">
            <a href="{{ route('privacy-policy') }}" class="btn btn-privacy">
                <i class="bi bi-shield-lock"></i>
                Kebijakan Privasi
            </a>
        </div>
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center"
                data-scrollax-parent="true">
                <div class="col-md-8 text-center">
                    <h1 class="mb-4 hero-title-custom">
                        <span class="hero-line-1">Sistem Informasi</span>
                        <span class="hero-line-2">Monitoring Administrasi Layanan Terintegrasi</span>
                        <span class="hero-line-3">KPPN 059 Majene</span>
                    </h1>
                    <p class="hero-btn-custom">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        <a href="#" class="btn btn-secondary">Dokumen WBK-WBBM</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <h2 class="mb-3 font-weight-bold">Tentang SIMALAQBI 059</h2>
                    <p>
                        SIMALAQBI059 merupakan Sistem Informasi Monitoring Administrasi Layanan Terintegrasi
                        yang dikembangkan untuk mendukung digitalisasi proses kerja di KPPN Majene.
                        Aplikasi ini membantu meningkatkan efisiensi, transparansi, serta kemudahan
                        dalam pengelolaan layanan administrasi dan monitoring kegiatan dalam satu wadah aplikasi.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 d-flex align-self-stretch ftco-animate fadeInUp ftco-animated">
                    <div class="media block-6 services p-3 py-4 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center mb-3">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="media-body px-3">
                            <h3 class="heading">Absensi PPNPN</h3>
                            <p>Mencatat kehadiran pegawai secara digital dan real-time, sehingga memudahkan monitoring
                                kedisiplinan serta rekapitulasi data absensi secara otomatis.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-self-stretch ftco-animate fadeInUp ftco-animated">
                    <div class="media block-6 services p-3 py-4 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center mb-3"><i
                                class="fas fa-user-clock"></i></div>
                        <div class="media-body px-3">
                            <h3 class="heading">Pengajuan Lembur PPNPN</h3>
                            <p>memungkinkan pegawai mengajukan lembur secara online yang kemudian dapat diverifikasi dan
                                disetujui oleh atasan, sehingga proses menjadi lebih tertib dan terdokumentasi dengan
                                baik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-self-stretch ftco-animate fadeInUp ftco-animated">
                    <div class="media block-6 services p-3 py-4 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center mb-3"><i
                                class="fas fa-calendar-check"></i></div>
                        <div class="media-body px-3">
                            <h3 class="heading">Reminder Absensi</h3>
                            <p>Terintegrasi dengan Google Kalender berfungsi memberikan pengingat otomatis kepada
                                pegawai terkait jadwal absensi, sehingga membantu meningkatkan kedisiplinan dan
                                mengurangi kelalaian.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-self-stretch ftco-animate fadeInUp ftco-animated">
                    <div class="media block-6 services p-3 py-4 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center mb-3"><i
                                class="fas fa-book"></i></div>
                        <div class="media-body px-3">
                            <h3 class="heading">Buku Tamu Digital</h3>
                            <p>Mencatat data kunjungan tamu secara elektronik, sehingga lebih rapi, mudah ditelusuri,
                                dan mendukung keamanan serta dokumentasi kunjungan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-self-stretch ftco-animate fadeInUp ftco-animated">
                    <div class="media block-6 services p-3 py-4 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center mb-3"><i
                                class="fas fa-calendar-plus"></i></div>
                        <div class="media-body px-3">
                            <h3 class="heading">Cuti Tahunan Tambahan Pegawai</h3>
                            <p>mempermudah pegawai dalam mengajukan cuti secara online serta memudahkan proses
                                persetujuan oleh atasan secara sistematis.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-self-stretch ftco-animate fadeInUp ftco-animated">
                    <div class="media block-6 services p-3 py-4 d-block text-center">
                        <div class="icon d-flex justify-content-center align-items-center mb-3"><i
                                class="fas fa-chart-line"></i></div>
                        <div class="media-body px-3">
                            <h3 class="heading">Dashboard Pemenuhan Dokumen WBK-WBBM</h3>
                            <p>Sebagai alat monitoring kelengkapan dokumen secara terpusat, sehingga memudahkan
                                pengawasan, evaluasi, dan memastikan seluruh persyaratan terpenuhi secara tepat waktu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row align-items-center">

                <!-- ================= LEFT : SLIDER ================= -->
                <div class="col-md-6 ftco-animate">
                    <div id="simalaqbiCarousel" class="carousel slide carousel-fade" data-ride="carousel"
                        data-interval="4000" data-pause="hover">

                        <!-- ===== Indicators ===== -->
                        <ol class="carousel-indicators">
                            @foreach ($images as $key => $image)
                                <li data-target="#simalaqbiCarousel" data-slide-to="{{ $key }}"
                                    class="{{ $key == 0 ? 'active' : '' }}">
                                </li>
                            @endforeach
                        </ol>

                        <div class="carousel-inner rounded shadow">

                            @foreach ($images as $key => $image)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">

                                    <div class="carousel-img-wrapper">
                                        <img src="{{ asset('backend/landing/slider/' . $image->getFilename()) }}"
                                            class="d-block w-100" alt="Slide {{ $key + 1 }}">
                                    </div>

                                </div>
                            @endforeach

                        </div>

                        <!-- Controls -->
                        <a class="carousel-control-prev" href="#simalaqbiCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>

                        <a class="carousel-control-next" href="#simalaqbiCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>

                    </div>
                </div>

                <!-- ================= RIGHT : PROGRESS BAR ================= -->
                <div class="col-md-6 ftco-animate">
                    <h3 class="mb-4 font-weight-bold">Progress Capaian Dokumen WBK-WBBM KPPN Majene 2026</h3>

                    @foreach ($categories as $key => $kategori)
                        <p>{{ $kategori->name }}</p>
                        <div class="progress mb-3" style="height: 20px;">
                            <div class="progress-bar bg-primary" role="progressbar"
                                style="width: {{ $kategori->progress() }}%;"
                                aria-valuenow="{{ $kategori->progress() }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $kategori->progress() }}%
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    <footer class="ftco-footer ftco-bg-dark ftco-section" background-attachment:fixed;">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">

                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<strong class="px-1 sitename">ParticipantMagangHub2</strong> <span>All Rights
                            Reserved</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>
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
    <script>
        $('#simalaqbiCarousel').carousel({
            interval: 4000,
            pause: "hover",
            wrap: true
        });
    </script>
    <script src="{{ asset('backend/landing/js/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/landing/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend/landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/landing/js/main.js') }}"></script>

</body>

</html>
