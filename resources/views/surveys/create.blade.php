@extends('master')

@section('content')
    <div class="container-fluid">

        <h5 class="mb-3 fw-semibold">Manajemen Survei</h5>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success py-2 px-3 small">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-3">

            {{-- ================= CARD 1 ================= --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0">

                    <div class="card-header py-2 px-3 bg-primary text-white small fw-semibold">
                        Buat Survey
                    </div>

                    <div class="card-body p-3">

                        {{-- FORM --}}
                        <form action="{{ route('surveys.store') }}" method="POST">
                            @csrf

                            <div class="mb-2">
                                <input type="text" name="title" class="form-control form-control-sm"
                                    placeholder="Judul Survey" required>
                            </div>

                            <div class="mb-2">
                                <textarea name="description" class="form-control form-control-sm" placeholder="Deskripsi"></textarea>
                            </div>

                            <div class="mb-2">
                                <select name="type" class="form-select form-select-sm" required>
                                    <option value="">-- Tipe --</option>
                                    <option value="pns">PNS</option>
                                    <option value="ppnpn">PPNPN</option>
                                </select>
                            </div>

                            <button class="btn btn-primary btn-sm w-100">
                                Simpan
                            </button>
                        </form>

                        <hr class="my-3">

                        {{-- DATA TABLE
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($surveys as $survey)
                                        <tr>
                                            <td>{{ $survey->title }}</td>
                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    {{ strtoupper($survey->type) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th width="80">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($surveys as $survey)
                                        <tr>
                                            <td>{{ $survey->title }}</td>

                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    {{ strtoupper($survey->type) }}
                                                </span>
                                            </td>

                                            <td>
                                                <button class="btn btn-sm btn-warning"
                                                    onclick="editSurvey({{ $survey->id }}, '{{ $survey->title }}', '{{ $survey->type }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ================= CARD 2 ================= --}}

            <div class="col-md-6">
                <div class="card shadow-sm border-0">

                    <div class="card-header py-2 px-3 bg-success text-white small fw-semibold">
                        Target Pegawai
                    </div>

                    <div class="card-body p-3">

                        {{-- FORM --}}
                        <form action="{{ route('surveys.target') }}" method="POST">
                            @csrf

                            {{-- PILIH SURVEY --}}
                            <div class="mb-2">
                                <select name="survey_id" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih Survey --</option>
                                    @foreach ($surveys as $survey)
                                        <option value="{{ $survey->id }}">{{ $survey->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- CHECKBOX PEGAWAI --}}
                            <div class="mb-2">
                                <label class="form-label small mb-1">Pilih Pegawai</label>

                                {{-- PILIH SEMUA --}}
                                <div class="form-check mb-1">
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                    <label class="form-check-label small" for="checkAll">
                                        Pilih Semua
                                    </label>
                                </div>

                                {{-- LIST PEGAWAI --}}
                                <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                    <div class="row g-1">

                                        @foreach ($employees as $emp)
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input employee-checkbox" type="checkbox"
                                                        name="employees[]" value="{{ $emp->id }}"
                                                        id="emp{{ $emp->id }}">

                                                    <label class="form-check-label small" for="emp{{ $emp->id }}">
                                                        {{ $emp->employee_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                            {{-- BUTTON --}}
                            <button class="btn btn-success btn-sm w-100">
                                Simpan
                            </button>
                        </form>

                        <hr class="my-3">

                        {{-- INFO MINI --}}
                        <div class="small text-muted">
                            Total Pegawai: {{ $employees->count() }}
                        </div>
                        {{-- <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Pegawai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($surveys as $survey)
                                        <tr>
                                            <td>{{ $survey->title }}</td>

                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    {{ strtoupper($survey->type) }}
                                                </span>
                                            </td>

                                            <td>
                                                @if ($survey->targets->count())
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach ($survey->targets as $target)
                                                            <span class="badge bg-light text-dark border">
                                                                {{ $target->employee->employee_name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted small">Belum ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Pegawai</th>
                                        <th width="100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($surveys as $survey)
                                        <tr>
                                            <td>{{ $survey->title }}</td>

                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    {{ strtoupper($survey->type) }}
                                                </span>
                                            </td>

                                            <td>
                                                @if ($survey->targets->count())
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach ($survey->targets as $target)
                                                            <span class="badge bg-light text-dark border">
                                                                {{ $target->employee->employee_name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted small">Belum ada</span>
                                                @endif
                                            </td>

                                            <td>
                                                {{-- EDIT --}}
                                                <button class="btn btn-warning btn-sm"
                                                    onclick="editTarget({{ $survey->id }}, {{ json_encode($survey->targets->pluck('employee_id')) }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- DELETE --}}
                                                <form action="{{ route('Targetsurveys.destroy', $survey->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin hapus survey ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ================= CARD 3 ================= --}}
        <div class="row g-3 mt-1">
            <div class="col-md-12">

                <div class="card shadow-sm border-0">

                    <div class="card-header py-2 px-3 bg-warning small fw-semibold">
                        Tambah Pertanyaan
                    </div>

                    <div class="card-body p-3">

                        {{-- FORM --}}
                        <form action="{{ route('surveys.question') }}" method="POST">
                            @csrf

                            <div class="mb-2">
                                <select name="survey_id" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih Survey --</option>
                                    @foreach ($surveys as $survey)
                                        <option value="{{ $survey->id }}">{{ $survey->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="question-wrapper">
                                <div class="mb-2">
                                    <input type="text" name="questions[]" class="form-control form-control-sm"
                                        placeholder="Pertanyaan..." required>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary btn-sm mb-2" onclick="addQuestion()">
                                + Tambah
                            </button>

                            <button class="btn btn-warning btn-sm w-100">
                                Simpan
                            </button>
                        </form>

                        <hr class="my-3">

                        {{-- DATA PERTANYAAN --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Survey</th>
                                        <th>Pertanyaan</th>
                                        <th width="80">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($surveys as $survey)
                                        @foreach ($survey->questions ?? [] as $q)
                                            <tr>
                                                <td>{{ $survey->title }}</td>
                                                <td>{{ $q->question }}</td>

                                                <td>
                                                    <form action="{{ route('questions.destroy', $q->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin hapus pertanyaan ini?')">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- MODAL EDIT SURVEY -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header py-2">
                        <h6 class="modal-title">Edit Survey</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-2">
                            <label class="small">Judul</label>
                            <input type="text" name="title" id="editTitle" class="form-control form-control-sm"
                                required>
                        </div>

                        <div class="mb-2">
                            <label class="small">Tipe</label>
                            <select name="type" id="editType" class="form-select form-select-sm" required>
                                <option value="pns">PNS</option>
                                <option value="ppnpn">PPNPN</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>

                        <button type="submit" class="btn btn-primary btn-sm">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="targetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="targetForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header py-2">
                        <h6 class="modal-title">Edit Target Pegawai</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="border rounded p-2" style="max-height: 250px; overflow-y:auto;">
                            <div class="row g-1">

                                @foreach ($employees as $emp)
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input emp-checkbox"
                                                name="employees[]" value="{{ $emp->id }}"
                                                id="edit_emp{{ $emp->id }}">

                                            <label class="form-check-label small" for="edit_emp{{ $emp->id }}">
                                                {{ $emp->employee_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    </div>

                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>

                        <button type="submit" class="btn btn-primary btn-sm">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>



    <script>
        function addQuestion() {
            let html = `
        <div class="mb-2">
            <input type="text" name="questions[]" 
                class="form-control form-control-sm"
                placeholder="Pertanyaan..." required>
        </div>
    `;
            document.getElementById('question-wrapper').insertAdjacentHTML('beforeend', html);
        }
    </script>
    {{-- SCRIPT --}}
    <script>
        // CHECK ALL
        document.getElementById('checkAll').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.employee-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
    <script>
        function editSurvey(id, title, type) {
            document.getElementById('editTitle').value = title;
            document.getElementById('editType').value = type;

            // set action form
            document.getElementById('editForm').action = '/surveys/' + id;

            // tampilkan modal
            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }
    </script>
    <script>
        function editTarget(surveyId, selectedEmployees) {

            // reset semua checkbox
            document.querySelectorAll('.emp-checkbox').forEach(cb => cb.checked = false);

            // centang sesuai data lama
            selectedEmployees.forEach(id => {
                let el = document.getElementById('edit_emp' + id);
                if (el) el.checked = true;
            });

            // set form action
            document.getElementById('targetForm').action = '/surveys/target/' + surveyId;

            // tampilkan modal
            let modal = new bootstrap.Modal(document.getElementById('targetModal'));
            modal.show();
        }
    </script>
@endsection
