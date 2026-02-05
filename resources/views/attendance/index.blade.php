@php
    $layout = in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'master' : 'master-no-sidebar';
@endphp

@extends($layout)

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">📸 Absensi Pegawai</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning text-left" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    Pastikan Untuk Aktifkan <strong>lokasi/GPS</strong> dan <strong>Kamera</strong> terlebih dahulu, sebelum
                    melakukan
                    Konfirmasi Absensi.
                </div>
                <div id="clock" class="mb-2 text-primary fw-bold"></div>

                @php
                    $isCheckIn = $attendanceToday && $attendanceToday->check_in_time;
                @endphp

                {{-- SHIFT --}}
                @if (!$isCheckIn)
                    {{-- PRESENSI DATANG --}}
                    <select id="shift" class="form-control mb-2">
                        <option value="">-- Pilih Shift --</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">
                                {{ $shift->shift_name }}
                                ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                @else
                    {{-- PRESENSI PULANG (SHIFT TERKUNCI) --}}
                    <input type="hidden" id="shift" value="{{ $attendanceToday->work_shift_id }}">

                    <div class="alert alert-info mb-2">
                        <strong>Shift:</strong>
                        {{ $attendanceToday->workShift->shift_name }}
                        ({{ \Carbon\Carbon::parse($attendanceToday->workShift->start_time)->format('H:i') }}
                        -
                        {{ \Carbon\Carbon::parse($attendanceToday->workShift->end_time)->format('H:i') }})
                        <br>
                        <small class="text-muted">Shift otomatis dikunci</small>
                    </div>
                @endif

                <video id="video" width="100%" autoplay class="border mb-2"></video>
                <canvas id="canvas" class="d-none"></canvas>

                <button class="btn btn-primary w-100 mb-2" onclick="openCamera()">
                    Buka Kamera
                </button>

                <button class="btn btn-success w-100" id="btnSubmit" onclick="submitAttendance()">
                    {{ $attendanceToday && $attendanceToday->check_in_time ? 'Presensi Pulang' : 'Presensi Datang' }}
                </button>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const attendanceType = "{{ $attendanceToday && $attendanceToday->check_in_time ? 'PULANG' : 'DATANG' }}";

        function showAlert(icon, title, text) {
            Swal.fire({
                icon,
                title,
                text,
                confirmButtonColor: '#4e73df'
            });
        }

        let latitude = null;
        let longitude = null;
        let stream = null;

        setInterval(() => {
            document.getElementById('clock').innerText =
                new Date().toLocaleString('id-ID');
        }, 1000);

        navigator.geolocation.getCurrentPosition(
            pos => {
                latitude = pos.coords.latitude;
                longitude = pos.coords.longitude;
            },
            () => {
                showAlert('warning', 'GPS Tidak Aktif', 'Aktifkan GPS untuk melanjutkan');
            }
        );

        function openCamera() {
            if (!navigator.mediaDevices?.getUserMedia) {
                showAlert('error', 'Tidak Didukung', 'Browser tidak mendukung kamera');
                return;
            }

            navigator.mediaDevices.getUserMedia({
                    video: true,
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

        function submitAttendance() {

            if (!latitude || !longitude) {
                showAlert('warning', 'Lokasi Belum Terbaca', 'Aktifkan GPS');
                return;
            }

            const shift = document.getElementById('shift').value;
            if (!shift) {
                showAlert('info', 'Shift Belum Dipilih', 'Pilih shift kerja');
                return;
            }

            const video = document.getElementById('video');
            if (!video.srcObject) {
                showAlert('warning', 'Kamera Belum Aktif', 'Buka kamera dulu');
                return;
            }

            const canvas = document.getElementById('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob(blob => {
                const formData = new FormData();
                formData.append('photo', blob, 'absen.jpg');
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
                    .then(res => res.json())
                    .then(res => {
                        Swal.fire({
                            icon: res.status === false ? 'error' : 'success',
                            title: res.status === false ? 'Gagal' : 'Berhasil',
                            text: res.message
                        }).then(() => {
                            if (res.status !== false) location.reload();
                        });
                    })
                    .catch(() => {
                        showAlert('error', 'Error', 'Terjadi kesalahan server');
                    });
            });
        }
    </script>
@endsection
