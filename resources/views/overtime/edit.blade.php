@extends('master-no-sidebar')

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-warning">
                    ✏️ Edit Pengajuan Lembur
                </h5>
            </div>

            <div class="card-body">

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('overtime.update', $overtime->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- TANGGAL --}}
                    <div class="form-group mb-3">
                        <label>Tanggal Lembur</label>
                        <input type="date" name="overtime_date" class="form-control"
                            value="{{ old('overtime_date', $overtime->overtime_date->format('Y-m-d')) }}" required>
                    </div>

                    {{-- JAM MULAI --}}
                    <div class="form-group mb-3">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control"
                            value="{{ old('start_time', $overtime->start_time) }}" required>
                    </div>

                    {{-- JAM SELESAI --}}
                    <div class="form-group mb-3">
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control"
                            value="{{ old('end_time', $overtime->end_time) }}" required>
                    </div>

                    {{-- FOTO --}}
                    <div class="form-group mb-3">
                        <label>Foto Lembur</label>

                        {{-- Foto Lama --}}
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $overtime->photo) }}"
                                style="max-width:200px; border-radius:8px;">
                        </div>

                        {{-- Upload Foto Baru --}}
                        <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png"
                            onchange="previewImage(event)">

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti foto.
                        </small>

                        {{-- Preview Foto Baru --}}
                        <div class="mt-3">
                            <img id="preview" style="display:none; max-width:200px; border-radius:8px;">
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('overtime.index') }}" class="btn btn-primary">
                            ← Kembali
                        </a>

                        <button type="submit" class="btn btn-success">
                            💾 Update Pengajuan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- Preview Script --}}
    <script>
        function previewImage(event) {
            let reader = new FileReader();
            reader.onload = function() {
                let output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
