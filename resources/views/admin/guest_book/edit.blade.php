@php
    $layout = in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'master' : 'master-no-sidebar';
@endphp

@extends($layout)

@section('css')
    <style>
        /* Ukuran teks dropdown */
        .select2-container .select2-selection--multiple {
            font-size: 1rem;
            /* small */
            padding: 4px;
        }

        /* Ukuran teks placeholder */
        .select2-container .select2-selection__placeholder {
            font-size: 0.8rem !important;
        }

        /* Ukuran teks item pencarian */
        .select2-container .select2-search__field {
            font-size: 0.8rem !important;
        }

        /* Ukuran teks hasil pencarian */
        .select2-results__option {
            font-size: 0.8rem;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Resepsionis - Edit Data Tamu</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('guest_book_update', $guest->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Tamu --}}
                    <div class="form-group">
                        <label>Nama Tamu</label>
                        <input type="text" name="guest_name" class="form-control @error('guest_name') is-invalid @enderror"
                            value="{{ old('guest_name', $guest->guest_name) }}">
                        @error('guest_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor HP --}}
                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" name="number_phone"
                            class="form-control @error('number_phone') is-invalid @enderror"
                            value="{{ old('number_phone', $guest->number_phone) }}">
                        @error('number_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Instansi --}}
                    {{-- <div class="form-group">
                        <label>Nama Instansi/Satker</label>
                        <input type="text" name="agency" class="form-control @error('agency') is-invalid @enderror"
                            value="{{ old('agency', $guest->agency) }}">
                        @error('agency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div class="form-group">
                        <label>Nama Instansi / Satker</label>
                        <select name="agency_id" class="form-control @error('agency_id') is-invalid @enderror">

                            <option value="">-- Pilih Instansi --</option>
                            @foreach ($agency as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('agency_id', $guest->agency_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->agency_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('agency_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Tujuan --}}
                    <div class="form-group">
                        <label>Tujuan</label>
                        <input type="text" name="objective" class="form-control @error('objective') is-invalid @enderror"
                            value="{{ old('objective', $guest->objective) }}">
                        @error('objective')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jam Datang --}}
                    <div class="form-group">
                        <label>Jam Datang</label>
                        <input type="time" name="arrival_time"
                            class="form-control @error('arrival_time') is-invalid @enderror"
                            value="{{ old('arrival_time', $guest->arrival_time) }}">
                        @error('arrival_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pegawai Yang Ingin Ditemui --}}
                    <div class="form-group">
                        <label>Pegawai Yang Ingin Ditemui</label>
                        <select name="employee_ids[]" class="form-control form-control-lg select2" multiple>

                            @php
                                // Data pegawai yang sudah dipilih sebelumnya
                                $selectedEmployees = $guest->employees->pluck('id')->toArray();
                            @endphp

                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ in_array($emp->id, old('employee_ids', $selectedEmployees)) ? 'selected' : '' }}>
                                    {{ $emp->employee_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>

                </form>
            </div>
        </div>
    </div>
@endsection



@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Cari Pegawai",
                allowClear: true
            });
        });
    </script>
@endsection
