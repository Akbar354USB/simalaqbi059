<!DOCTYPE html>
<html>

<head>
    <title>Survey {{ strtoupper($type) }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .rating-group {
            display: flex;
            gap: 5px;
            /* lebih rapat */
        }

        .rating-group label {
            cursor: pointer;
            border: 1px solid #ddd;
            padding: 4px 6px;
            border-radius: 5px;
            font-size: 11px;
            transition: 0.2s;
            min-width: 28px;
            text-align: center;
        }

        .rating-group input:checked+label {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container text-center mt-5">

        <h3>Selamat Datang</h3>
        <p class="text-muted">Silakan isi survey berikut dengan jujur</p>

        <button class="btn btn-primary" onclick="startSurvey()">
            Mulai Survey
        </button>

    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="surveyModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('survey.submit') }}">
                    @csrf
                    <input type="hidden" name="survey_id" value="{{ $survey->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title">{{ $survey->title }}</h5>
                    </div>

                    <div class="modal-body">

                        {{-- LEGEND --}}
                        <div class="mb-3 small text-muted text-center">
                            <b>Skor:</b>
                            1 SK | 2 K | 3 C | 4 B | 5 SB
                        </div>

                        <div id="stepContent"></div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="prevStep()">Prev</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="nextStep()">Next</button>
                        <button type="submit" class="btn btn-success btn-sm d-none" id="finishBtn">
                            Selesai
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        let targets = @json($targets);
        let questions = @json($survey->questions);

        let step = 0;

        // 🔥 STORE JAWABAN
        let answersStore = {};

        function startSurvey() {
            let modal = new bootstrap.Modal(document.getElementById('surveyModal'));
            modal.show();
            renderStep();
        }

        function renderStep() {

            let emp = targets[step].employee;

            let html = `<h6 class="mb-3">${emp.employee_name}</h6>`;

            questions.forEach((q, index) => {

                html += `
            <div class="mb-3">
                <label class="small fw-semibold">
                    ${index + 1}. ${q.question}
                </label>

                <div class="rating-group mt-1">
                    ${[1,2,3,4,5].map(score => {

                        let checked = 
                            answersStore[emp.id] &&
                            answersStore[emp.id][q.id] == score
                            ? 'checked' : '';

                        return `
                                                                <div>
                                                                    <input type="radio"
                                                                        name="answers[${emp.id}][${q.id}]"
                                                                        value="${score}"
                                                                        id="q${q.id}_${emp.id}_${score}"
                                                                        ${checked}
                                                                        hidden>

                                                                    <label for="q${q.id}_${emp.id}_${score}">
                                                                        ${score}
                                                                    </label>
                                                                </div>
                                                            `;
                    }).join('')}
                </div>
            </div>
        `;
            });

            document.getElementById('stepContent').innerHTML = html;

            // 🔥 SIMPAN PERUBAHAN SAAT CLICK
            document.querySelectorAll('#stepContent input[type=radio]').forEach(el => {
                el.addEventListener('change', function() {

                    let empId = this.name.match(/\[(\d+)\]/)[1];
                    let qId = this.name.match(/\[(\d+)\]\[(\d+)\]/)[2];

                    if (!answersStore[empId]) {
                        answersStore[empId] = {};
                    }

                    answersStore[empId][qId] = this.value;
                });
            });

            document.getElementById('finishBtn')
                .classList.toggle('d-none', step !== targets.length - 1);
        }

        function nextStep() {

            let emp = targets[step].employee;

            if (!answersStore[emp.id] || Object.keys(answersStore[emp.id]).length < questions.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Semua pertanyaan harus diisi!'
                });
                return;
            }

            if (step < targets.length - 1) {
                step++;
                renderStep();
            }
        }

        function prevStep() {
            if (step > 0) {
                step--;
                renderStep();
            }
        }
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {

            e.preventDefault(); // tahan submit dulu

            // 🔥 VALIDASI FINAL (semua pegawai harus terisi)
            for (let t of targets) {
                let empId = t.employee.id;

                if (!answersStore[empId] || Object.keys(answersStore[empId]).length < questions.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum lengkap',
                        text: 'Semua pegawai harus dinilai!'
                    });
                    return;
                }
            }

            // 🔥 HAPUS INPUT LAMA (jika ada)
            document.querySelectorAll('.dynamic-answer').forEach(el => el.remove());

            // 🔥 INJECT SEMUA JAWABAN KE FORM
            let form = this;

            for (let empId in answersStore) {
                for (let qId in answersStore[empId]) {

                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `answers[${empId}][${qId}]`;
                    input.value = answersStore[empId][qId];
                    input.classList.add('dynamic-answer');

                    form.appendChild(input);
                }
            }

            // 🔥 SWEET ALERT SEBELUM SUBMIT
            Swal.fire({
                title: 'Selesai?',
                text: 'Kirim jawaban survey sekarang?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}'
            });
        </script>
    @endif

</body>

</html>
