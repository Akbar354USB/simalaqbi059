@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📸 Tambah Data Wajah Pegawai</h5>
            </div>

            <div class="card-body">

                {{-- Error Validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('employee-faces.store') }}" onsubmit="return validateFace()">
                    @csrf

                    {{-- Pegawai --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pegawai</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->name }} - {{ $employee->nip }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hidden Descriptor --}}
                    <input type="hidden" name="face_descriptor" id="face_descriptor">

                    {{-- Camera --}}
                    <div class="text-center mb-3">
                        <video id="video" width="320" height="240" class="border rounded bg-dark shadow-sm"
                            autoplay muted></video>
                    </div>

                    {{-- Status --}}
                    <div class="text-center mb-3">
                        <span id="face-status" class="badge bg-secondary">
                            ⏳ Memuat model wajah...
                        </span>
                    </div>

                    {{-- Action --}}
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary" onclick="captureFace()">
                            📸 Ambil Wajah
                        </button>

                        <button type="submit" id="btnSubmit" class="btn btn-primary" disabled>
                            💾 Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script defer src="https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <script>
        /* =====================================================
       ELEMENTS
    ===================================================== */
        const video = document.getElementById('video');
        const faceStatus = document.getElementById('face-status');
        const btnSubmit = document.getElementById('btnSubmit');
        const descriptorInput = document.getElementById('face_descriptor');

        /* =====================================================
           CAMERA
        ===================================================== */
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => video.srcObject = stream)
            .catch(() => {
                faceStatus.textContent = '❌ Kamera tidak tersedia';
                faceStatus.className = 'badge bg-danger';
            });

        /* =====================================================
           LOAD MODELS (FAST)
        ===================================================== */
        let modelReady = false;
        const MODEL_URL = '/models';

        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]).then(() => {
            modelReady = true;
            faceStatus.textContent = '✅ Model wajah siap';
            faceStatus.className = 'badge bg-success';
        });

        /* =====================================================
           CAPTURE FACE (OPTIMIZED)
        ===================================================== */
        async function captureFace() {

            if (!modelReady) {
                alert('⏳ Model wajah belum siap');
                return;
            }

            faceStatus.textContent = '🔍 Mendeteksi wajah...';
            faceStatus.className = 'badge bg-warning';

            const detection = await faceapi
                .detectSingleFace(
                    video,
                    new faceapi.TinyFaceDetectorOptions({
                        inputSize: 224,
                        scoreThreshold: 0.5
                    })
                )
                .withFaceDescriptor();

            if (!detection) {
                faceStatus.textContent = '❌ Wajah tidak terdeteksi';
                faceStatus.className = 'badge bg-danger';
                btnSubmit.disabled = true;
                return;
            }

            descriptorInput.value = JSON.stringify(Array.from(detection.descriptor));

            faceStatus.textContent = '✅ Wajah berhasil direkam';
            faceStatus.className = 'badge bg-success';
            btnSubmit.disabled = false;
        }

        /* =====================================================
           FINAL VALIDATION
        ===================================================== */
        function validateFace() {
            if (!descriptorInput.value) {
                alert('❌ Silakan ambil wajah terlebih dahulu');
                return false;
            }
            return true;
        }
    </script>
@endsection
