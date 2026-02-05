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
                        <label class="form-label">Nama Shift</label>
                        <input type="text" name="shift_name"
                            class="form-control @error('shift_name') is-invalid @enderror"
                            value="{{ old('shift_name', $workShift->shift_name) }}" required>
                        @error('shift_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="start_time"
                            class="form-control @error('start_time') is-invalid @enderror"
                            value="{{ old('start_time', \Carbon\Carbon::parse($workShift->start_time)->format('H:i')) }}"
                            required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
                            value="{{ old('end_time', \Carbon\Carbon::parse($workShift->end_time)->format('H:i')) }}"
                            required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update
                    </button>

                    <a href="{{ route('work-shifts.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection
