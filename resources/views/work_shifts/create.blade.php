@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Shift Kerja</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('work-shifts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Shift</label>
                        <input type="text" name="shift_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Mulai Shift</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Selesai Shift</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Durasi Hari Shift</label>
                        <input type="number" name="day_span" class="form-control" min="0" value="0" required>
                        <small class="text-muted">
                            0 = satu hari (normal)<br>
                            1 = lintas 1 hari (contoh: 17.15 - 07.15)<br>
                            2 = lintas 2 hari (contoh: Sabtu - Senin)
                        </small>
                    </div>

                    <button class="btn btn-success">Simpan</button>
                    <a href="{{ route('work-shifts.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
