<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kebijakan Privasi - Sistem Reminder Absensi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* body {
            background: #f5f7fb;
            font-family: "Segoe UI", sans-serif;
        }

        .header-policy {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            padding: 40px 0;
        }

        .policy-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .accordion-button {
            font-weight: 600;
            font-size: 16px;
        }

        .accordion-button:not(.collapsed) {
            background: #e9f2ff;
            color: #0d6efd;
        }

        .accordion-item {
            border: 0;
            border-bottom: 1px solid #eee;
        }

        .accordion-body {
            line-height: 1.7;
            color: #555;
        }

        .badge-update {
            background: #e9f2ff;
            color: #0d6efd;
            font-weight: 500;
        } */
        body {
            background: #f5f7fb;
            font-family: "Segoe UI", sans-serif;
        }

        /* HEADER */

        .header-policy {
            position: relative;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            padding: 70px 0;
            overflow: hidden;
        }

        /* Background decorative */

        .header-policy::before {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: -120px;
            right: -120px;
        }

        .header-policy::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -120px;
            left: -120px;
        }

        /* ICON */

        .header-icon {
            width: 70px;
            height: 70px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            font-size: 32px;
        }

        /* TITLE */

        .header-policy h2 {
            font-size: 32px;
            letter-spacing: .5px;
        }

        /* SUBTITLE */

        .subtitle {
            opacity: .9;
            font-size: 15px;
        }

        /* BADGE */

        .badge-update {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 14px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 13px;
            backdrop-filter: blur(4px);
        }

        /* CARD */

        .policy-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        /* ACCORDION */

        .accordion-button {
            font-weight: 600;
            font-size: 16px;
        }

        .accordion-button:not(.collapsed) {
            background: #e9f2ff;
            color: #0d6efd;
        }

        .accordion-item {
            border: 0;
            border-bottom: 1px solid #eee;
        }

        .accordion-body {
            line-height: 1.7;
            color: #555;
        }
    </style>


</head>

<body>


    <!-- Header -->

    {{-- <section class="header-policy text-center">
        <div class="container">

            <h2 class="fw-bold">Kebijakan Privasi</h2>

            <p class="mb-1">Aplikasi Sistem Reminder Absensi</p>

            <span class="badge badge-update">
                Terakhir diperbarui: 16 Maret 2026
            </span>

        </div>
    </section> --}}

    <section class="header-policy text-center">
        <div class="container">

            <div class="header-icon mb-3">
                <i class="bi bi-shield-check"></i>
            </div>

            <h2 class="fw-bold mb-2">Kebijakan Privasi</h2>

            <p class="subtitle mb-3">
                Aplikasi Sistem Reminder Absensi
            </p>

            <span class="badge badge-update">
                <i class="bi bi-clock-history me-1"></i>
                Terakhir diperbarui: 16 Maret 2026
            </span>

        </div>
    </section>



    <!-- Content -->

    <div class="container my-5">

        <div class="card policy-card">
            <div class="card-body p-4">

                <div class="accordion" id="privacyAccordion">


                    <!-- 1 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#p1">
                                1. Apa tujuan Kebijakan Privasi ini?
                            </button>
                        </h2>

                        <div id="p1" class="accordion-collapse collapse show" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Aplikasi Sistem Reminder Absensi menghargai dan melindungi privasi setiap pengguna.
                                Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan,
                                dan melindungi informasi pengguna, khususnya terkait penggunaan layanan Google Calendar
                                melalui mekanisme OAuth.

                                Dengan menggunakan aplikasi ini, pengguna dianggap telah memahami dan menyetujui praktik
                                pengelolaan data sebagaimana dijelaskan dalam kebijakan ini.

                            </div>
                        </div>
                    </div>



                    <!-- 2 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#p2">
                                2. Informasi apa saja yang diakses oleh aplikasi ini?
                            </button>
                        </h2>

                        <div id="p2" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Aplikasi ini menggunakan Google OAuth 2.0 untuk menghubungkan akun Google pengguna
                                dengan sistem kami.

                                Informasi yang dapat diakses meliputi:

                                <ul>
                                    <li>Informasi dasar akun Google seperti nama dan alamat email</li>
                                    <li>Akses terbatas untuk membuat dan mengelola event pada Google Calendar pengguna
                                    </li>
                                </ul>

                                Aplikasi tidak mengakses data lain dari akun Google pengguna di luar izin yang
                                diberikan.

                            </div>
                        </div>
                    </div>



                    <!-- 3 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#p3">
                                3. Untuk apa data pengguna digunakan?
                            </button>
                        </h2>

                        <div id="p3" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Data yang diperoleh melalui layanan Google digunakan hanya untuk fungsi utama aplikasi,
                                yaitu:

                                <ul>
                                    <li>Menghubungkan akun pengguna dengan sistem</li>
                                    <li>Membuat event pengingat absensi pada Google Calendar pengguna</li>
                                    <li>Mengirimkan pengingat jadwal absensi kepada pegawai</li>
                                    <li>Memastikan sistem pengingat absensi berjalan dengan baik</li>
                                </ul>

                                Data Google tidak digunakan untuk tujuan lain di luar fungsi utama aplikasi.

                            </div>
                        </div>
                    </div>



                    <!-- 4 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#p4">
                                4. Apakah aplikasi menyimpan data kalender pengguna?
                            </button>
                        </h2>

                        <div id="p4" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Aplikasi tidak menyimpan isi kalender pengguna secara permanen.

                                Beberapa informasi yang dapat disimpan secara terbatas meliputi:

                                <ul>
                                    <li>ID pengguna sistem</li>
                                    <li>Email pengguna</li>
                                    <li>Token autentikasi OAuth untuk koneksi layanan</li>
                                </ul>

                                Token tersebut disimpan secara aman dan hanya digunakan untuk kebutuhan integrasi dengan
                                Google Calendar.

                            </div>
                        </div>
                    </div>



                    <!-- 5 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#p5">
                                5. Apakah data pengguna dibagikan kepada pihak lain?
                            </button>
                        </h2>

                        <div id="p5" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Kami tidak menjual, menyewakan, atau membagikan data pengguna kepada pihak ketiga.

                                Informasi pengguna hanya digunakan untuk menjalankan fungsi layanan pada aplikasi ini
                                dan tidak akan ditransfer kepada pihak luar kecuali jika diwajibkan oleh peraturan hukum
                                yang berlaku.

                            </div>
                        </div>
                    </div>



                    <!-- 6 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#p6">
                                6. Bagaimana keamanan data pengguna dijaga?
                            </button>
                        </h2>

                        <div id="p6" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Kami menerapkan langkah-langkah keamanan untuk melindungi data pengguna, antara lain:

                                <ul>
                                    <li>Menggunakan protokol komunikasi aman (HTTPS)</li>
                                    <li>Menerapkan pengamanan akses sistem</li>
                                    <li>Membatasi akses data hanya pada sistem yang membutuhkan</li>
                                </ul>

                                Kami berkomitmen menjaga keamanan data pengguna dari akses yang tidak sah.

                            </div>
                        </div>
                    </div>



                    <!-- 7 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#p7">
                                7. Apakah aplikasi mematuhi kebijakan penggunaan data Google?
                            </button>
                        </h2>

                        <div id="p7" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Penggunaan dan transfer informasi yang diterima dari Google APIs oleh aplikasi ini akan
                                mematuhi ketentuan:

                                <b>Google API Services User Data Policy</b>, termasuk persyaratan <b>Limited Use</b>.

                                Data yang diperoleh dari Google hanya digunakan untuk menyediakan atau meningkatkan
                                fitur aplikasi yang berkaitan langsung dengan fungsi utama sistem.

                            </div>
                        </div>
                    </div>



                    <!-- 8 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#p8">
                                8. Apa hak pengguna terkait data mereka?
                            </button>
                        </h2>

                        <div id="p8" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Pengguna memiliki hak untuk:

                                <ul>
                                    <li>Mencabut akses aplikasi melalui pengaturan akun Google</li>
                                    <li>Meminta penghapusan koneksi akun dari sistem</li>
                                    <li>Menghubungi pengelola sistem terkait penggunaan data</li>
                                </ul>

                                Jika akses dicabut, aplikasi tidak lagi dapat membuat atau mengelola event pada Google
                                Calendar pengguna.

                            </div>
                        </div>
                    </div>



                    <!-- 9 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#p9">
                                9. Apakah kebijakan privasi ini dapat berubah?
                            </button>
                        </h2>

                        <div id="p9" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Kebijakan Privasi ini dapat diperbarui sewaktu-waktu untuk menyesuaikan dengan perubahan
                                layanan maupun peraturan yang berlaku.
                                Setiap perubahan akan diumumkan melalui halaman ini.

                            </div>
                        </div>
                    </div>



                    <!-- 10 -->

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                data-bs-target="#p10">
                                10. Bagaimana cara menghubungi pengelola sistem?
                            </button>
                        </h2>

                        <div id="p10" class="accordion-collapse collapse" data-bs-parent="#privacyAccordion">
                            <div class="accordion-body">

                                Jika Anda memiliki pertanyaan terkait kebijakan privasi ini, silakan menghubungi:

                                <b>Admin Sistem SIMALAQBI 059</b><br>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
