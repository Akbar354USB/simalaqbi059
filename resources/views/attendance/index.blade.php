@php
    $layout = in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'master' : 'master-no-sidebar';
@endphp

@extends($layout)

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Absensi PPNPN</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning text-left" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    Pastikan Untuk Aktifkan <strong>lokasi/GPS</strong> dan <strong>Kamera</strong> terlebih dahulu, sebelum
                    melakukan
                    Konfirmasi Absensi.
                </div>
                <!--<div id="clock" class="mb-2 text-primary fw-bold text-center"></div>-->
                <div id="clock" class="bg-light-primary mb-2 text-primary fw-bold text-center fs-2"></div>

                <!--$isCheckIn = $attendanceToday && $attendanceToday->check_in_time;-->
                @php
                    $isCheckIn = !is_null($attendanceToday);
                @endphp

                {{-- SHIFT --}}
                @if (!$isCheckIn)
                    {{-- PRESENSI DATANG --}}
                    <select id="shift" class="form-control mb-2">
                        <option value="">-- Pilih Shift --</option>
                        <!--@foreach ($shifts as $shift)
    -->
                        <!--    <option value="{{ $shift->id }}">-->
                        <!--        {{ $shift->shift_name }}-->
                        <!--    </option>-->
                        <!--
    @endforeach-->
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}" data-start="{{ $shift->start_time }}"
                                data-end="{{ $shift->end_time }}">
                                {{ $shift->shift_name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    {{-- PRESENSI PULANG (SHIFT TERKUNCI) --}}
                    <input type="hidden" id="shift" value="{{ $attendanceToday->work_shift_id }}">

                    <div class="alert alert-info mb-2">
                        <strong>Shift:</strong>
                        {{ $attendanceToday->workShift->shift_name }}
                        <br>
                        <small class="text-muted">Shift otomatis dikunci</small>
                    </div>
                @endif
                <video id="video" width="100%" autoplay playsinline class="border rounded mb-2"></video>
                <canvas id="canvas" class="d-none"></canvas>

                {{-- Tombol Ambil Foto --}}
                <button class="btn btn-primary w-100 mb-2" id="btnCapture">
                    📸 Ambil Gambar
                </button>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function showAlert(icon, title, text) {
                Swal.fire({
                    icon,
                    title,
                    text,
                    confirmButtonColor: '#4e73df'
                });
            }

            const attendanceType = "{{ $attendanceToday ? 'PULANG' : 'DATANG' }}";
            const confirmText = attendanceType === 'DATANG' ?
                '✅ Presensi Datang' :
                '✅ Presensi Pulang';

            let latitude = null;
            let longitude = null;
            let stream = null;
            let capturedBlob = null;

            // ================= JAM =================
            setInterval(() => {
                const clock = document.getElementById('clock');
                if (clock) {
                    clock.innerText = new Date().toLocaleString('id-ID');
                }
            }, 1000);

            // ================= GPS =================
            navigator.geolocation.getCurrentPosition(
                pos => {
                    latitude = pos.coords.latitude;
                    longitude = pos.coords.longitude;
                },
                () => {
                    showAlert('warning', 'GPS Tidak Aktif', 'Aktifkan GPS terlebih dahulu');
                }
            );

            // ================= AUTO START CAMERA =================
            function startCamera() {
                if (!navigator.mediaDevices?.getUserMedia) {
                    showAlert('error', 'Tidak Didukung', 'Browser tidak mendukung kamera');
                    return;
                }

                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "user"
                        },
                        audio: false
                    })
                    .then(s => {
                        stream = s;
                        const video = document.getElementById('video');
                        video.srcObject = s;
                        video.play();
                    })
                    .catch(() => {
                        showAlert('error', 'Kamera Gagal', 'Pastikan izin kamera aktif & HTTPS');
                    });
            }

            startCamera();

            // ================= CEK SHIFT =================
            window.isShiftStarted = function() {

                if (attendanceType === 'PULANG') return true;

                const shiftSelect = document.getElementById('shift');
                if (!shiftSelect || !shiftSelect.value) {
                    showAlert('info', 'Shift Belum Dipilih', 'Silakan pilih shift kerja terlebih dahulu');
                    return false;
                }

                const option = shiftSelect.options[shiftSelect.selectedIndex];
                const startTime = option.dataset.start;

                if (!startTime) {
                    showAlert('error', 'Data Shift Tidak Valid', 'Hubungi admin');
                    return false;
                }

                const [h, m] = startTime.split(':').map(Number);
                const shiftStart = h * 60 + m;

                const now = new Date();
                const nowMinutes = now.getHours() * 60 + now.getMinutes();

                if (nowMinutes < shiftStart) {
                    showAlert('warning',
                        'Presensi Belum Dibuka',
                        `Presensi dibuka mulai pukul ${startTime}`
                    );
                    return false;
                }

                return true;
            }

            // ================= AMBIL FOTO =================
            const btnCapture = document.getElementById('btnCapture');

            btnCapture.addEventListener('click', function() {

                if (!isShiftStarted()) return;

                if (!latitude || !longitude) {
                    showAlert('warning', 'Lokasi Belum Terbaca', 'Aktifkan GPS');
                    return;
                }

                const shift = document.getElementById('shift').value;
                if (!shift) {
                    showAlert('info', 'Shift Belum Dipilih', 'Silakan pilih shift');
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

                    Swal.fire({
                        title: 'Konfirmasi Absensi',
                        html: `
                    <p>Pastikan foto sudah sesuai</p>
                    <img src="${imageUrl}" class="img-fluid rounded mb-3"/>
                `,
                        showCancelButton: true,
                        confirmButtonText: confirmText,
                        cancelButtonText: '🔄 Ambil Ulang',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        width: 600
                    }).then(result => {
                        if (result.isConfirmed) {
                            sendAttendance();
                        }
                    });

                }, 'image/jpeg');
            });

            // ================= KIRIM ABSENSI =================
            function sendAttendance() {

                const shift = document.getElementById('shift').value;

                const fileName =
                    attendanceType.toLowerCase() +
                    '_' +
                    new Date().getTime() +
                    '.jpg';

                const formData = new FormData();
                formData.append('photo', capturedBlob, fileName);
                formData.append('latitude', latitude);
                formData.append('longitude', longitude);
                formData.append('work_shift_id', shift);
                formData.append('type', attendanceType);
                formData.append('_token', '{{ csrf_token() }}');

                Swal.fire({
                    title: 'Mengirim Absensi...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch("{{ route('attendance.store') }}", {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(res => {

                        if (res.status === false) {
                            showAlert('error', 'Gagal', res.message);
                            return;
                        }

                        Swal.fire('Berhasil', res.message, 'success')
                            .then(() => {
                                window.location.href =
                                    "{{ route('attendance.index') }}?t=" + new Date().getTime();
                            });

                    })
                    .catch(() => {
                        showAlert('error', 'Error', 'Terjadi kesalahan server');
                    });
            }

        });
    </script>
@endsection
