@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Unit Kerja</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('work-units.store') }}" method="POST">
                    @csrf

                    {{-- Unit Kerja --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Unit Kerja</label>
                        <input type="text" name="work_unit" class="form-control @error('work_unit') is-invalid @enderror"
                            value="{{ old('work_unit') }}" required>

                        @error('work_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pilih Pimpinan --}}
                    <div class="mb-3">
                        <label class="form-label">Pimpinan Unit Kerja</label>
                        <select name="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror"
                            required>
                            <option value=""></option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->employee_name }}
                                    @if ($employee->nip)
                                        - {{ $employee->nip }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('employee_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('work-units.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: '-- Pilih Pimpinan Unit Kerja --',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
