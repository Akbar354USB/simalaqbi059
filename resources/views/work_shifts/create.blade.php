@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Shitf Kerja</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('work-shifts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Nama Shift</label>
                        <input type="text" name="shift_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>

                    <button class="btn btn-success">Simpan</button>
                    <a href="{{ route('work-shifts.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
