{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Welcome-SIMALAQBI059</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('backend/favicon.png') }}" rel="icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('backend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/vendor/aos/aos.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('backend/assets/css/main.css') }}" rel="stylesheet">

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="index.html" class="logo d-flex align-items-center me-auto">
                <img src="{{ asset('backend/simona059 copy.png') }}" alt="">
            </a>
        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center"
                        data-aos="zoom-out">
                        <h1>SIMALAQBI 059</h1>
                        <p>Sistem Informasi Monitoring Admnistrasi Layanan Baik dan Integratif KPPN 059 Majene</p>
                        <div class="d-flex">
                            <a href="{{ route('login') }}" class="btn-get-started">Get Started</a>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                        <img src="{{ asset('backend/hero-img.png') }}" class="img-fluid animated" alt="">
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Skills Section -->
        <section id="skills" class="skills section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row">

                    <div class="col-lg-6 d-flex text-center">
                        <img src="{{ asset('backend/progress.svg') }}" class="img-fluid" alt="" width="80%">
                    </div>

                    <div class="col-lg-6 pt-4 pt-lg-0 content">

                        <h3>Progres Pencapian WBK - WBBM Kantor Pelayanan Perbendaharaan Majene</h3>
                        <p class="fst-italic">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.
                        </p>

                        <div class="skills-content skills-animation">
                            @foreach ($categories as $key => $kategori)
                                <div class="progress">
                                    <span class="skill"><span>{{ $kategori->name }}</span> <i
                                            class="val">{{ $kategori->progress() }}%</i></span>
                                    <div class="progress-bar-wrap">
                                        <div class="progress-bar" role="progressbar"
                                            aria-valuenow="{{ $kategori->progress() }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div><!-- End Skills Item -->
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

        </section><!-- /Skills Section -->

        <!-- About Section -->
        <section id="about" class="about section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>About Us</h2>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-6 content text-center" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('backend/STRUKTUR.png') }}" alt="" width="80%" class="img-fluid">
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <p> Sebelum Proklamasi kemerdekaan RI, KPPN dikenal dengan nama CKC atau Central Kantoor voor
                            deComptabiliteit. Setelah berakhirnya masa penjajahan, KPPN Majene resmi didirikan pada
                            Tanggal 1 Juni 1966 dengan nama Kantor Pembantu Bendahara Negara (KPBN) Majene dan dipimpin
                            oleh kepala kantor pertama bernama Abdul Fattah, BA. Pada saat itu KPBN masih dibawahi oleh
                            Direktorat Jenderal Anggaran Departemen Keuangan.<br> Pada Tahun berdirinya, KPPN Majene
                            yang
                            berkedudukan di Kabupaten Majene masih merupakan bagian dari wilayah Provinsi Sulawesi
                            Selatan dan memiliki 3 wilayah pembayaran yaitu Kabupaten Majene, Kab. Polmas (saat ini
                            sudah mekar menjadi Kab. Polman dan Kab. Mamasa), dan Kab. Mamuju (saat ini sudah mekar
                            menjadi Kab. Mamuju Tengah, dan Kab. Mamuju Utara). Luasnya wilayah Provinsi Sulawesi
                            Selatan dan urgensi keberadaan kantor pembayaran untuk daerah-daerah di bagian barat
                            Sulawesi menjadi dasar dibentuknya KPPN Majene, sehingga KPPN Majene menjadi KPPN pertama di
                            Sulawesi bagian barat dengan kode KPPN 059. Keberadaan KPPN Majene menjadi angin segar bagi
                            satuan kerja di wilayah Sulawesi bagian barat.

                        <ul>
                            <li>- Tahun 1970, Kantor Pembantu Bendahara Negara (KPBN) Majene ditingkatkan menjadi Kantor
                                Bendahara Negara (KBN) Majene</li>
                            <li>- Tahun 1975-1982 KBN Majene dipecah menjadi dua, yaitu Kantor Perbendaharaan Negara
                                (KPN)
                                dan Kantor Kas Negara (KKN)</li>
                            <li>- Tahun 1990, KPN dan KKN Majene dilebur menjadi satu kembali dengan nama Kantor
                                Perbendaharaan dan Kas Negara (KPKN) Majene</li>
                            <li>- Tanggal 23 Juni 2004, terbit Keputusan Menteri Keuangan Nomor 303/KMK.01/2004 tentang
                                perubahan nama KPKN Majene menjadi Kantor Pelayanan Perbendaharaan Negara Majene atau
                                yang disingkat KPPN Majene.</li>
                            <li>- Tanggal 5 Oktober 2004, Sulawesi mengalami pemekaran daerah yaitu terbentuknya
                                provinsi
                                Sulawesi Barat dengan Ibu Kota Mamuju sehingga wilayah pembayaran KPPN Majene menjadi
                                Kabupaten Majene, Kab. Polewali Mandar, dan Kab. Mamasa sedangkan Kab. Mamuju pindah
                                wilayah
                                pembayaran ke KPPN Mamuju.</li>
                        </ul>
                        </p>
                    </div>

                </div>

            </div>

        </section><!-- /About Section -->

    </main>

    <footer id="footer" class="footer">

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright by</span> <strong class="px-1 sitename">ParticipantMagangHub2</strong> <span>All Rights
                    Reserved</span>
            </p>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('backend/assets/vendor/aos/aos.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('backend/assets/js/main.js') }}"></script>

</body>

</html> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Simalaqbi - 059</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/landing/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/landing/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/landing/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/landing/css/custom.css') }}">
</head>

<body>
    <!-- END nav -->

    <div class="hero-wrap"
        style="background-image: url('https://simalaqbi059.id/backend/landing/backgroundlanding.png'); background-attachment:fixed;">
        <div class="hero-logo-top">
            <img src="https://simalaqbi059.id/backend/simalaqbi.png" alt="Logo SIMALAQBI">
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
