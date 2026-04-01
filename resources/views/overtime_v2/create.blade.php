@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-2">
            <div class="card-header">
                <h5 class="text-primary">Lembur (Overtime)</h5>
            </div>

            <div class="card-body">

                <div class="alert alert-warning">
                    Aktifkan <strong>GPS</strong> dan <strong>Kamera</strong>
                </div>

                <div id="clock" class="bg-light-primary mb-2 text-primary fw-bold text-center fs-2"></div>

                @php
                    $isCheckIn = !is_null($overtimeToday);
                @endphp

                @if (!$isCheckIn)
                    <textarea id="purpose" class="form-control mb-2" placeholder="Tujuan lembur"></textarea>
                @else
                    <div class="alert alert-info mb-2">
                        <strong>Tujuan Lembur:</strong><br>
                        {{ $overtimeToday->purpose }}
                    </div>
                @endif

                {{-- Kamera --}}
                <video id="video" width="100%" autoplay playsinline class="border rounded mb-2"></video>
                <canvas id="canvas" class="d-none"></canvas>

                <button class="btn w-100 {{ !$isCheckIn ? 'btn-success' : 'btn-danger' }}" id="btnCapture">
                    @if (!$isCheckIn)
                        Mulai Lembur
                    @else
                        Akhiri Lembur
                    @endif
                </button>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        const overtimeDate = "{{ now()->toDateString() }}";
    </script>
    <script>
        let latitude = null;
        let longitude = null;
        let stream = null;
        let capturedBlob = null;

        setInterval(() => {
            const clock = document.getElementById('clock');
            if (clock) {
                clock.innerText = new Date().toLocaleString('id-ID');
            }
        }, 1000);

        navigator.geolocation.getCurrentPosition(
            pos => {
                latitude = pos.coords.latitude;
                longitude = pos.coords.longitude;
            },
            () => alert('Aktifkan GPS')
        );

        function startCamera() {
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user"
                }
            }).then(s => {
                stream = s;
                video.srcObject = s;
            });
        }
        startCamera();

        btnCapture.onclick = function() {

            let purpose = '';

            @if (!$isCheckIn)
                purpose = document.getElementById('purpose').value;

                if (!purpose) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tujuan Belum Diisi',
                        text: 'Silakan isi tujuan lembur terlebih dahulu'
                    }).then(() => {
                        document.getElementById('purpose').focus();
                    });
                    return;
                }
            @endif

            if (!latitude) {
                Swal.fire({
                    icon: 'warning',
                    title: 'GPS Belum Aktif',
                    text: 'Aktifkan lokasi terlebih dahulu',
                });
                return;
            }

            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob(blob => {

                capturedBlob = blob;
                const imageUrl = URL.createObjectURL(blob);

                // 🔥 POPUP PREVIEW
                Swal.fire({
                    title: 'Konfirmasi Foto',
                    html: `
                <p>Pastikan foto sudah jelas</p>
                <img src="${imageUrl}" class="img-fluid rounded mb-3"/>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Kirim',
                    cancelButtonText: 'Ulangi',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    width: 600
                }).then(result => {

                    if (result.isConfirmed) {
                        sendOvertime(purpose);
                    }

                });

            }, 'image/jpeg');
        };

        function sendOvertime(purpose) {

            let formData = new FormData();
            formData.append('photo', capturedBlob);
            formData.append('latitude', latitude);
            formData.append('longitude', longitude);
            formData.append('overtime_date', overtimeDate);

            @if (!$isCheckIn)
                formData.append('purpose', purpose);
            @endif

            formData.append('_token', '{{ csrf_token() }}');

            Swal.fire({
                title: 'Mengirim...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch("{{ route('overtime_v2.store') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {

                    if (!res.status) {
                        Swal.fire('Gagal', res.message, 'error');
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message
                    }).then(() => location.reload());
                })
                .catch(() => {
                    Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                });
        }
    </script>
@endsection
