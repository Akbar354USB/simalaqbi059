@extends('master')

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="card shadow-sm mt-4" style="max-width: 420px; width:100%">
            <div class="card-body text-center">

                <h5 class="mb-1">Verifikasi Wajah</h5>
                <small class="text-muted">{{ $employee->employee_name }}</small>

                <div class="mt-3 position-relative">
                    <video id="video" autoplay playsinline muted class="w-100 rounded border"
                        style="aspect-ratio: 3/4; object-fit: cover">
                    </video>

                    <div id="status" class="position-absolute top-50 start-50 translate-middle text-white fw-bold"
                        style="display:none">
                        Memuat kamera...
                    </div>
                </div>

                <button id="capture" class="btn btn-primary w-100 mt-3">
                    Rekam Wajah
                </button>

                <small class="d-block text-muted mt-2">
                    Pastikan wajah terlihat jelas dan cahaya cukup
                </small>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module">
        import vision from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest";

        const {
            FaceLandmarker,
            FilesetResolver
        } = vision;

        const video = document.getElementById("video");
        const captureBtn = document.getElementById("capture");
        const statusText = document.getElementById("status");

        let faceLandmarker;

        async function init() {
            statusText.style.display = "block";
            statusText.innerText = "Mengaktifkan kamera...";

            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user"
                }
            });
            video.srcObject = stream;

            statusText.innerText = "Memuat model AI...";

            const resolver = await FilesetResolver.forVisionTasks(
                "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest/wasm"
            );

            faceLandmarker = await FaceLandmarker.createFromOptions(resolver, {
                baseOptions: {
                    modelAssetPath: "https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task"
                },
                runningMode: "VIDEO",
                numFaces: 1
            });

            statusText.style.display = "none";
            console.log("FaceLandmarker siap");
        }

        captureBtn.addEventListener("click", async () => {
            if (!faceLandmarker) {
                alert("Model belum siap");
                return;
            }

            captureBtn.disabled = true;
            captureBtn.innerText = "Memverifikasi...";

            const results = await faceLandmarker.detectForVideo(
                video,
                performance.now()
            );

            if (!results.faceLandmarks || results.faceLandmarks.length === 0) {
                alert("Wajah tidak terdeteksi");
                captureBtn.disabled = false;
                captureBtn.innerText = "Rekam Wajah";
                return;
            }

            let embedding = [];
            results.faceLandmarks[0].forEach(p => {
                embedding.push(p.x, p.y, p.z);
            });

            fetch("{{ url('/employee-face/' . $employee->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        embedding
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(() => alert("Gagal menyimpan wajah"))
                .finally(() => {
                    captureBtn.disabled = false;
                    captureBtn.innerText = "Rekam Wajah";
                });
        });

        init();
    </script>
@endsection
