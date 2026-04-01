@extends('master')

@section('content')
    <div class="container">
        <h3>Edit Lembur</h3>

        <div class="card card-body">
            <form action="{{ route('overtime_v2.update', $overtime->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Pegawai</label>
                    <select name="employee_id" class="form-control" required>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $overtime->employee_id == $emp->id ? 'selected' : '' }}>
                                {{ $emp->employee_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal Lembur</label>
                    <input type="date" name="overtime_date" class="form-control"
                        value="{{ old('overtime_date', \Carbon\Carbon::parse($overtime->overtime_date)->format('Y-m-d')) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label>Tujuan</label>
                    <textarea name="purpose" class="form-control" required>{{ $overtime->purpose }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Durasi (menit)</label>
                    <input type="number" name="duration" class="form-control" value="{{ $overtime->duration }}">
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $overtime->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $overtime->status == 'approved' ? 'selected' : '' }}>Setujui</option>
                        <option value="rejected" {{ $overtime->status == 'rejected' ? 'selected' : '' }}>Tolak</option>
                    </select>
                </div>

                <button class="btn btn-success">Update</button>
                <a href="{{ route('overtime_v2.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
