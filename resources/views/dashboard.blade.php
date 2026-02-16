@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="alert alert-success text-left" role="alert">
            <i class="fas fa-info-circle"></i>
            Selamat Datang <strong>{{ Auth::user()->name }}</strong> di SIMALAQBI059. Sistem Monitoring dan Administrasi
            KPPN
            Majene.
        </div>
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-6 col-xl-3">
                <div class="card h-100 bg-primary bg-opacity-25">
                    <div class="card-body">
                        <h6 class="mb-2 f-w-400 text-muted">Pegawai Terdaftar</h6>
                        <h4 class="mb-3">
                            {{ $totalPegawai }}
                            <span class="badge bg-light-primary border border-primary">
                                <i class="ti ti-users"></i>
                            </span>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-6 col-xl-3">
                <div class="card h-100 bg-success bg-opacity-25">
                    <div class="card-body">
                        <h6 class="mb-2 f-w-400 text-muted">Daftar Tamu</h6>
                        <h4 class="mb-3">
                            {{ $totalTamu }}
                            <span class="badge bg-light-success border border-success">
                                <i class="ti ti-book"></i>
                            </span>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-6 col-xl-3">
                <div class="card h-100 bg-warning bg-opacity-25">
                    <div class="card-body">
                        <h6 class="mb-2 f-w-400 text-muted">Progress WBK-WBBM</h6>
                        <h4 class="mb-3">
                            {{ $overallProgress }} %
                            <span class="badge bg-light-warning border border-warning">
                                <i class="ti ti-trending-up"></i>
                            </span>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="mb-2 f-w-400 text-muted">Satker Terdaftar</h6>
                        <h4 class="mb-3">
                            {{ $satker }}
                            <span class="badge bg-light-danger border border-danger">
                                <i class="ti ti-building"></i>
                            </span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Progress Bar -->
            <div class="col-xl-8 col-lg-7">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Progress Pencapaian WBK-WBBM
                        </h6>
                    </div>

                    <div class="card-body">

                        @foreach ($categories as $key => $kategori)
                            <h4 class="small font-weight-bold">
                                {{ $kategori->name }} <span class="float-right">{{ $kategori->progress() }}%</span>
                            </h4>

                            <div class="progress mb-4">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $kategori->progress() }}%" aria-valuenow="{{ $kategori->progress() }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        @endforeach
                        <p class="text-muted small">
                            Target: 100% (WBK â†’ WBBM)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Products Sold</h6>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <button onclick="subscribePush()" class="btn btn-primary float-end">
        Aktifkan Reminder Absensi
    </button>

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

        // ===============================
        // Subscribe Push Notification
        // ===============================
        async function subscribePush() {

            // ❌ Browser tidak support
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                alert('Browser tidak mendukung Push Notification');
                return;
            }

            // 🔔 Minta izin notifikasi
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                alert('Izin notifikasi ditolak');
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

            /**
             * 🔥 PENTING !!!
             * Laravel WebPush BUTUH contentEncoding
             */
            const data = subscription.toJSON();
            data.contentEncoding = 'aesgcm';

            // 🚀 Kirim ke server
            const response = await fetch("{{ route('push.subscribe') }}", {
                method: "POST",
                credentials: "same-origin", // WAJIB agar auth terbaca
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            });

            // 🔍 Debug response
            console.log('STATUS:', response.status);
            const respText = await response.text();
            console.log('RESPONSE:', respText);

            if (!response.ok) {
                alert('Gagal menyimpan subscription (' + response.status + ')');
                return;
            }

            alert('Reminder absensi aktif');
        }
    </script>
@endsection
