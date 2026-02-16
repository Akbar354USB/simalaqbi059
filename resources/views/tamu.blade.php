<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reminder Absensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons (Default Mantis) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-5">

                        <div class="mb-4">
                            <i class="ti ti-bell fs-1 text-success"></i>
                        </div>

                        <h3 class="fw-bold mb-3">
                            Selamat Datang
                        </h3>

                        <p style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; line-height: 1.8;"
                            class="text-muted">
                            <strong>Yth. {{ Auth::user()->name }}</strong>, fitur
                            <strong>Reminder Hadirku 059</strong> telah berhasil diaktifkan.
                            Notifikasi pengingat kehadiran akan dikirimkan secara langsung melalui aplikasi sesuai
                            dengan jadwal shift yang terdaftar pada sistem.

                            Untuk memastikan pengingat dapat diterima dengan baik, mohon agar Saudara/i melakukan klik
                            pada tombol <strong>“Aktifkan Reminder Absensi”</strong>.
                            Selain itu, silakan memastikan bahwa pengaturan notifikasi aplikasi pada perangkat
                            masing-masing telah diaktifkan dan disesuaikan sebagaimana mestinya.

                            Aktivasi notifikasi ini diharapkan dapat mendukung kedisiplinan serta kelancaran pelaksanaan
                            tugas di lingkungan kerja.
                        </p>
                        <button onclick="subscribePush()" class = "btn btn-outline-success">Aktifkan Reminder
                            Absensi</button>

                        <hr class="my-4">

                        <div class="mt-3">

                            @if (Auth::user()->role == 'superadmin')
                                <!-- Alert Dashboard -->
                                <div class="alert alert-info text-center" role="alert"> <i
                                        class="fas fa-info-circle"></i> Klik tombol <strong>Dashboard</strong>
                                    untuk
                                    melanjutkan ke halaman utama sistem SIMONA59. </div>
                                @endif @if (Auth::user()->role == 'ppnpn')
                                    <!-- Alert Dashboard -->
                                    <div class="alert alert-info text-center" role="alert"> <i
                                            class="ti ti-info-circle me-1"></i>
                                        Klik tombol <strong>Absen PPNPN</strong>
                                        untuk melanjutkan ke halaman utama Absensi PPNPN SIMONA59. </div>
                                @endif

                                <!-- Alert Logout -->
                                <div class="alert alert-warning text-center" role="alert"><i
                                        class="ti ti-alert-triangle me-1"></i>
                                    Klik <strong>Logout</strong> untuk
                                    keluar
                                    dari sistem. Silahkan Menghubungi admin, Jika mendapat kendala dan Reminder
                                    tidak
                                    masuk
                                    ke device masing-masing. </div>

                                <div class="d-flex justify-content-center flex-wrap gap-2 mt-3">
                                    @if (Auth::user()->role == 'superadmin')
                                        <a href="{{ route('home') }}" class="btn btn-primary">
                                            <i class="ti ti-layout-dashboard me-1"></i> Dashboard
                                        </a>
                                    @endif

                                    @if (Auth::user()->role == 'ppnpn')
                                        <a href="{{ route('attendance.index') }}" class="btn btn-primary">
                                            <i class="ti ti-user-clock me-1"></i> Absen PPNPN
                                        </a>
                                    @endif

                                    <a href="#" class="btn btn-outline-danger"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="ti ti-logout me-1"></i> Logout
                                    </a>
                                </div>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===============================
        // Helper WAJIB untuk VAPID Key
        // ===============================
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/');

            const rawData = atob(base64);
            return Uint8Array.from(
                [...rawData].map(char => char.charCodeAt(0))
            );
        }
        async function subscribePush() {

            // ❌ Browser tidak support
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Browser tidak mendukung Push Notification'
                });
                return;
            }

            // 🔔 Minta izin notifikasi
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Izin Ditolak',
                    text: 'Izin notifikasi ditolak oleh pengguna'
                });
                return;
            }

            // 🧩 Ambil service worker
            const registration = await navigator.serviceWorker.ready;

            // 🔑 Subscribe push
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                    "{{ config('webpush.vapid.public_key') }}"
                )
            });

            // 🔥 Tambahkan contentEncoding
            const data = subscription.toJSON();
            data.contentEncoding = 'aesgcm';

            // 🚀 Kirim ke server
            const response = await fetch("{{ route('push.subscribe') }}", {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            });

            console.log('STATUS:', response.status);
            const respText = await response.text();
            console.log('RESPONSE:', respText);

            if (!response.ok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menyimpan subscription (' + response.status + ')'
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Reminder absensi aktif'
            });
        }
    </script>
</body>

</html>
