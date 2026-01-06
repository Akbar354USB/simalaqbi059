@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Edit Data Shift Kerja</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('work-shifts.update', $workShift->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Nama Shift</label>
                        <input type="text" name="shift_name" value="{{ $workShift->shift_name }}" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" value="{{ $workShift->start_time }}" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" value="{{ $workShift->end_time }}" class="form-control"
                            required>
                    </div>

                    <button class="btn btn-success">Update</button>
                    <a href="{{ route('work-shifts.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
