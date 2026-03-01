@extends('master-no-sidebar')

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    🕒 Form Pengajuan Lembur
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('overtime.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- TANGGAL --}}
                    <div class="form-group mb-3">
                        <label>Tanggal Lembur</label>
                        <input type="date" name="overtime_date" class="form-control" value="{{ old('overtime_date') }}"
                            required>
                    </div>

                    {{-- JAM MULAI --}}
                    <div class="form-group mb-3">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}"
                            required>
                    </div>

                    {{-- JAM SELESAI --}}
                    <div class="form-group mb-3">
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                    </div>

                    {{-- UPLOAD FOTO --}}
                    <div class="form-group mb-3">
                        <label>Upload Foto Lembur</label>
                        <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png"
                            onchange="previewImage(event)" required>

                        <small class="text-muted">
                            File harus JPG/JPEG/PNG maksimal 2MB
                        </small>

                        <div class="mt-3">
                            <img id="preview" style="display:none; max-width:200px; border-radius:8px;">
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard-absen') }}" class="btn btn-primary">
                            ← Kembali
                        </a>


                        <button type="submit" class="btn btn-success">
                            💾 Simpan Pengajuan
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
{{-- ERROR --}}

@section('js')
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: `
                        <ul style="text-align:left;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        </script>
    @endif
@endsection
